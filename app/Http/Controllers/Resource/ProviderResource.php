<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Exception;
use Storage;
use Setting;
use App\Provider;
use App\ProviderDocument;
use App\Payouts;
use App\ProviderEmails;
use App\UserRequests;
use App\UserRequestPayment;
use App\Helpers\Helper;
use Mail;
use Illuminate\Support\Facades\Config;

class ProviderResource extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::with('service', 'accepted', 'cancelled')
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:providers,email|email|max:255',
            'mobile' => '',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

            $provider = $request->all();

            $provider['password'] = bcrypt($request->password);
            if ($request->hasFile('picture')) {
                $provider['avatar'] = $request->picture->store('provider/profile');
            }

            $provider = Provider::create($provider);

            return back()->with('flash_success', 'Provider Details Saved Successfully');
        } catch (Exception $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.provider-details', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.edit', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => '',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try {

            $provider = Provider::findOrFail($id);

            if ($request->hasFile('picture')) {
                $provider->avatar = $request->picture->store('provider/profile');
            }
            $provider->first_name = $request->first_name;
            $provider->last_name = $request->last_name;
            $provider->mobile = $request->mobile;
            $provider->save();

            return redirect()->route('admin.provider.index')->with('flash_success', 'Provider Updated Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Provider::find($id)->delete();
            return back()->with('message', 'Provider deleted successfully');
        } catch (Exception $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        try {
            $Provider = Provider::findOrFail($id);
            if ($Provider->service) {
                $Provider->update(['status' => 'approved']);
                return back()->with('flash_success', "Provider Approved");
            } else {
                return redirect()->route('admin.provider.document.index', $id)->with('flash_error', "Provider has not been assigned a service type!");
            }
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function disapprove($id)
    {
        Provider::where('id', $id)->update(['status' => 'banned']);
        return back()->with('flash_success', "Provider Disapproved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id)
    {

        try {

            $requests = UserRequests::where('user_requests.provider_id', $id)
                ->RequestHistory()
                ->get();

            return view('admin.request.request-history', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id)
    {

        try {

            $requests = UserRequests::where('provider_id', $id)
                ->where('status', 'COMPLETED')
                ->with('payment')
                ->get();

            $rides = UserRequests::where('provider_id', $id)->with('payment')->orderBy('id', 'desc')->paginate(10);
            $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', $id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
                $query->where('provider_id', $id);
            })->select(\DB::raw(
                'SUM(ROUND(fixed) + ROUND(time_price)) as overall, SUM(ROUND(commision)) as commission'
            ))->get();


            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';

            return view('admin.providers.statement', compact('rides', 'cancel_rides', 'revenue'))
                ->with('page', $Provider->first_name . "'s Overall Statement " . $Joined);
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * provider bank detail add form.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function bank_details($id)
    {
        $Provider = $id;
        return view('admin.providers.add_bank_details', compact('Provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function add_bank_details(Request $request)
    {
        $this->validate($request, [
            'providerId' => 'required',
            'rib' => 'required',
            'troubleShooting' => 'required',
            'cashReceived' => 'required',
            'cb' => 'required',
            'commission' => 'required',
            'result' => 'required'
        ]);

        try {

            $provider = Provider::findOrFail($request->providerId);

            $payout = new Payouts;
            $payout->provider_id = $provider->id;
            $payout->rib = $request->rib;
            $payout->troubleShooting = $request->troubleShooting;
            $payout->cashReceived = $request->cashReceived;
            $payout->cb = $request->cb;
            $payout->commission = $request->commission;
            $payout->result = $request->result;
            $payout->save();

            return redirect()->route('admin.provider.index')->with('flash_success', 'Bank Details Added Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
    }

    /**
     * send email to provider form.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function send_email($id)
    {
        $Provider = $id;
        return view('admin.providers.send_emails', compact('Provider'));
    }

    //    func to send emails to provider
    public function send_provider_email(Request $request)
    {
        $request->email_body = str_replace('"../../../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $request->email_body = str_replace('"../../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $request->email_body = str_replace('"../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $emailContents = array(
            'subject' => $request->email_subject,
            'body' => $request->email_body
        );

        try {
            $provider = Provider::findOrFail($request->providerId);

            $this->saveHistory($request, $request->providerId);

            $status = $this->SendEmail($provider->email, $emailContents, 'emails.blukemail');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
        if ($status == 1) {
            return back()->with('flash_success', 'Emails Sent Successfully');
        } else {
            return back()->with('flash_error', 'Something Went Wrong. Emails Not Sent');
        }
    }

    //    send email function
    public function SendEmail($EmailTo, $emailContents, $body)
    {
        try {
            Config::set('mail.username', 'support@autocopain.fr');
            Config::set('mail.password', 'Editionsp39**');
            $data = array('email' => $EmailTo, 'subject' => $emailContents['subject'], 'emailContents' => $emailContents);
            Mail::send(['html' => $body], $data, function ($message) use ($data) {
                $message->from('support@autocopain.fr', 'AutoCopain');
                $message->to($data['email']);
                $message->subject($data['subject']);
            });
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function saveHistory($request, $provider_id)
    {
        $email = new ProviderEmails;
        $email->provider_id = $provider_id;
        $email->email_subject = trim($request->email_subject);
        $email->email_body = $request->email_body;
        $email->save();
        return 1;
    }

    /**
     * history of emails send to provider.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function email_history($id)
    {
        try {

            $history = ProviderEmails::where('provider_id', $id)->get();

            return view('admin.providers.email_history', compact('history'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    public function rib_store(Request $request)
    {
        $this->validate($request, [
            'providerId' => 'required',
            'rib' => 'required'
        ]);

        try {

            $provider = Provider::findOrFail($request->providerId);
            $provider->rib = $request->rib;
            $provider->save();

            return back()->with('flash_success', 'RIB Added Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'Provider Not Found');
        }
    }

    public function Accountstatement($id)
    {

        try {

            $requests = UserRequests::where('provider_id', $id)
                ->where('status', 'COMPLETED')
                ->with('payment')
                ->get();

            $rides = UserRequests::where('provider_id', $id)->with('payment')->orderBy('id', 'desc')->paginate(10);
            $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', $id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
                $query->where('provider_id', $id);
            })->select(\DB::raw(
                'SUM(ROUND(fixed) + ROUND(time_price)) as overall, SUM(ROUND(commision)) as commission'
            ))->get();


            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';

            return view('account.providers.statement', compact('rides', 'cancel_rides', 'revenue'))
                ->with('page', $Provider->first_name . "'s Overall Statement " . $Joined);
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function validate_doc($id)
    {
        $countDoc = ProviderDocument::where('provider_id', $id)->count();
        if ($countDoc > 0) {
            ProviderDocument::where('provider_id', $id)->update(['verification_status' => 1]);
            Provider::where('id', $id)->update(['status' => 'approved', 'admin_verified' => 0]);
        }

        return back()->with('flash_success', "Provider Document Approved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function reject_doc($id)
    {
        $countDoc = ProviderDocument::where('provider_id', $id)->count();
        if ($countDoc > 0) {
            ProviderDocument::where('provider_id', $id)->update(['verification_status' => 2]);
            Provider::where('id', $id)->update(['status' => 'banned', 'admin_verified' => 0]);
        } else {
            Provider::where('id', $id)->update(['admin_verified' => 1]);
        }
        return back()->with('flash_success', "Provider Document Disapproved");
    }


    public function update_document(Request $request, $provider, $id)
    {
        $this->validate($request, [
            'document' => 'mimes:jpg,jpeg,png,pdf',
        ]);
        try {

            $Document = ProviderDocument::where('provider_id', $provider)
                ->where('id', $id)
                ->firstOrFail();

            $Document->update([
                'url' => $request->document->store('provider/documents'),
                'status' => 'ASSESSING',
            ]);

            return back()->with('flash_success', "Provider Document Updated");
        } catch (ModelNotFoundException $e) {

            // ProviderDocument::create([
            //         'url' => $request->document->store('provider/documents'),
            //         'provider_id' => $provider,
            //         'status' => 'ASSESSING',
            //     ]);
            return back()->with('flash_error', "Failed Provider Document Updated" . json_encode($e));
        }
    }
}
