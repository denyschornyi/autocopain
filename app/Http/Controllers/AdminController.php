<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Provider;
use App\Settings;
use App\Admin;
use App\EmailHistory;
use App\UserRequestRating;
use App\UserPayment;
use App\ProviderService;
use App\UserRequests;
use App\ServiceType;
use App\UserRequestPayment;
use App\Payouts;
use App\Helpers\Helper;
use Auth;
use Exception;
use Carbon\Carbon;
use Storage;
use Setting;
use Mail;
use App\WalletRequest;
use App\ProviderWallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProviderResources\TripController;

class AdminController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function showEmailDetails() {
        //        get history of emails, which email send to providers or user or both
        $users = User::getEmailHistory();
        return view('admin.email.email_details', compact('users'));
    }

    //    show send email form
    public function showSendEmailForm() {
        return view('admin.email.send_emails');
    }

    //    func to send emails to users or provides or both
    public function sendEmailsToUsers(Request $request) {
        $emailTo = $request->input('email_to');
        $sendTo = array();
        $request->email_body = str_replace('"../../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $request->email_body = str_replace('"../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $emailContents = array(
            'subject' => $request->email_subject,
            'body' => $request->email_body
        );

//        $this->saveHistory($request);
        if ($emailTo == 1) {
            if ($request->provider_type == 1) {
                //rejected doc
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 2);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 2) {
                //no documents
                $providers = Provider::doesntHave('documents')->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 3) {
                //need validation
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 0);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 4) {
                //validated docs
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 1);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 5) {
                //get all active providers
                $providers = Provider::getEmailOfAllActiveProvers();
            }
            //get all active providers
//            $providers = Provider::getEmailOfAllActiveProvers();
            foreach ($providers as $provider) {
                $sendTo[] = $provider->email;
            }
        } else if ($emailTo == 2) {
            //          get all active users
            $users = User::getEmailOfAllActiveUsers();
            foreach ($users as $user) {
                $sendTo[] = $user->email;
            }
        } else {
            if ($request->provider_type == 1) {
                //rejected doc
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 2);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 2) {
                //no documents
                $providers = Provider::doesntHave('documents')->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 3) {
                //need validation
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 0);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 4) {
                //validated docs
                $providers = Provider::whereHas('documents', function ($query) {
                            $query->where('verification_status', 1);
                        })->where('isVerified', '=', 1)->get();
            } else if ($request->provider_type == 5) {
                //get all active providers
                $providers = Provider::getEmailOfAllActiveProvers();
            }
            //get all active providers and send emails to providers
//            $providers = Provider::getEmailOfAllActiveProvers();
            foreach ($providers as $provider) {
                $sendTo[] = $provider->email;
            }

            //           get all active users and send email
            $users = User::getEmailOfAllActiveUsers();
            foreach ($users as $user) {
                $sendTo[] = $user->email;
            }
        }

        $status = $this->SendEmail($sendTo, $emailContents, 'emails.blukemail');
        if ($status == 1) {
            return back()->with('flash_success', 'Emails Sent Successfully');
        } else {
            return back()->with('flash_error', 'Something Went Wrong. Emails Not Sent');
        }
    }

    //    send email function
    public function SendEmail($EmailTo, $emailContents, $body) {
        try {
            foreach ($EmailTo as $sendTo) {
                $data = array('email' => $sendTo, 'subject' => $emailContents['subject'], 'emailContents' => $emailContents);
                Mail::send(['html' => $body], $data, function ($message) use ($data) {
                    $message->from('info@autocopain.com', 'AutoCopain');
                    $message->to($data['email']);
                    $message->subject($data['subject']);
                });
            }
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function saveHistory($request) {
        $email = new EmailHistory;
        $email->emailTo = $request->email_to;
        $email->emailSubject = trim($request->email_subject);
        $email->emailBody = $request->email_body;
        $email->created_at = date("Y-m-d H:i:s");
        $email->save();
        return 1;
    }

    public function dashboard() {
        try {

            $rides = UserRequests::has('user')->orderBy('id', 'desc')->get();
            $cancel_rides = UserRequests::where('status', 'CANCELLED')->count();
            $latest_rides = UserRequests::has('user')->where('created_at', '>=', Carbon::now()->subDay())->count();
            $service = ServiceType::count();
            $revenue = UserRequestPayment::sum('total');
            $providers = Provider::take(10)->orderBy('rating', 'desc')->get();
            $newUsers = User::where('created_at', '>=', Carbon::now()->subDay())->count();
            $newProviders = Provider::where('created_at', '>=', Carbon::now()->subDay())->count();
            $Pendinglist = WalletRequest::select(['wallet_requests.*', 'providers.first_name as first_name', 'providers.last_name as last_name', 'providers.rib as rib'])
                    ->leftJoin('providers', 'wallet_requests.from_id', '=', 'providers.id')
                    ->where('wallet_requests.status', 'PENDING')
                    ->where('wallet_requests.request_from', 'provider')
                    ->count();
            //            $alerts = UserRequests::where('status', 'CANCELLED')->where('cancelled_by', 'PROVIDER')->count();
            $alerts = Provider::has('cancelled_count', '>=', 2)
                    ->count();


            $commissions = UserRequests::with('payment')->get();
            $total_commission = 0;
            if (count($commissions) > 0) {
                foreach ($commissions as $commission) {
                    $total_commission += $commission->payment['commision'];
                }
            }

            $admin_credit = 0;
            $provider_credit = 0;
            $provider_debit = 0;
            $discount = 0;

            $aa = UserRequestPayment::select(\DB::raw(
                                    'SUM(total) as admin_credit'
                    ))->firstOrFail();
            $admin_credit = $aa->admin_credit ? abs($aa->admin_credit) : 0;

            $aa = Provider::select(\DB::raw(
                                    'SUM(wallet_balance) as provider_credit'
                    ))->where('wallet_balance', '<', 0)->firstOrFail();
            $provider_credit = $aa->provider_credit ? abs($aa->provider_credit) : 0;

            $aa = Provider::select(\DB::raw(
                                    'SUM(wallet_balance) as provider_debit'
                    ))->where('wallet_balance', '>', 0)->firstOrFail();
            $provider_debit = $aa->provider_debit ? abs($aa->provider_debit) : 0;

            $aa = WalletRequest::select(DB::raw('SUM(amount) as discount'))
                    ->whereIn('status', ['PENDING'])
                    ->firstOrFail();
            $discount = $aa->discount ? abs($aa->discount) : 0;

            return view('admin.dashboard', compact('latest_rides', 'total_commission', 'alerts', 'Pendinglist', 'newProviders', 'newUsers', 'providers', 'service', 'rides', 'cancel_rides', 'revenue', 'admin_credit', 'provider_credit', 'provider_debit', 'discount'));
        } catch (Exception $e) {
            return redirect()->route('admin.user.index')->with('flash_error', 'Something Went Wrong with Dashboard!');
        }
    }

    public function cancel_report() {
        try {
            $cancelReport = Provider::has('cancelled_count', '>=', 2)
                    ->get();

            return view('admin.cancel_report', compact('cancelReport'));
        } catch (Exception $e) {
            return redirect()->route('admin.dashboard')->with('flash_error', 'Something Went Wrong with Report!');
        }
    }

    public function provider_score() {
        try {
            $score = Provider::where('rating', '<', 3)->get();

            return view('admin.score_report', compact('score'));
        } catch (Exception $e) {
            return redirect()->route('admin.dashboard')->with('flash_error', 'Something Went Wrong with Report!');
        }
    }

    public function mytests() {
        echo "hello testing";
        die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function user_map() {
        try {

            $Users = User::where('latitude', '!=', 0)->where('longitude', '!=', 0)->get();
            return view('admin.map.user_map', compact('Users'));
        } catch (Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function provider_map() {
        try {
            $Providers = Provider::where('latitude', '!=', 0)->where('longitude', '!=', 0)->has('service')->get();
            return view('admin.map.provider_map', compact('Providers'));
        } catch (Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function setting() {
        return view('admin.setting.site-setting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function setting_store(Request $request) {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'site_icon' => 'mimes:jpeg,jpg,bmp,png||max:5242880',
            'site_logo' => 'mimes:jpeg,jpg,bmp,png||max:5242880',
        ]);

        $settings = Settings::all();

        foreach ($settings as $setting) {

            $key = $setting->key;

            $temp_setting = Settings::find($setting->id);

            if ($temp_setting->key == 'site_icon') {

                if ($request->file('site_icon') == null) {
                    $icon = $temp_setting->value;
                } else {
                    if ($temp_setting->value) {
                        Helper::delete_picture($temp_setting->value);
                    }
                    $icon = Helper::upload_picture($request->file('site_icon'));
                }

                $temp_setting->value = $icon;
            } else if ($temp_setting->key == 'site_logo') {

                if ($request->file('site_logo') == null) {
                    $logo = $temp_setting->value;
                } else {
                    if ($temp_setting->value) {
                        Helper::delete_picture($temp_setting->value);
                    }
                    $logo = Helper::upload_picture($request->file('site_logo'));
                }

                $temp_setting->value = $logo;
            } else if ($temp_setting->key == 'email_logo') {

                if ($request->file('email_logo') == null) {
                    $logo = $temp_setting->value;
                } else {
                    if ($temp_setting->value) {
                        Helper::delete_picture($temp_setting->value);
                    }
                    $logo = Helper::upload_picture($request->file('email_logo'));
                }

                $temp_setting->value = $logo;
            } else if ($temp_setting->key == 'manual_request') {

                if ($request->$key == 1) {
                    $temp_setting->value = 1;
                }
            } else if ($temp_setting->key == 'CARD') {
                if ($request->$key == 'on') {
                    $temp_setting->value = 1;
                } else {
                    $temp_setting->value = 0;
                }
            } else if ($request->$key != '') {

                $temp_setting->value = $request->$key;
            }

            $temp_setting->save();
        }

        return back()->with('flash_success', 'Settings Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile() {
        return view('admin.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request) {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try {

            $admin = Admin::find(Auth::guard('admin')->user()->id);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->mobile = $request->mobile;

            if ($request->hasFile('picture')) {
                $admin->picture = Storage::url(Storage::putFile('admin/profile', $request->picture, 'public'));
            }

            $admin->gender = $request->gender;
            $admin->save();

            return redirect()->back()->with('flash_success', 'Profile Updated');
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
    public function password() {
        return view('admin.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request) {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

            $Admin = Admin::find(Auth::guard('admin')->user()->id);

            if (password_verify($request->old_password, $Admin->password)) {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success', 'Password Updated');
            } else {
                return redirect()->back()->with('flash_error', 'Incorrect Password');
            }
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
    public function payment() {
        try {
            $payments = UserRequests::where('paid', 1)
                    ->has('user')
                    ->has('provider')
                    ->has('payment')
                    ->orderBy('user_requests.created_at', 'desc')
                    ->get();

            return view('admin.payment.payment-history', compact('payments'));
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
    public function payment_setting() {
        return view('admin.payment.payment-setting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function help() {

        try {
            $str = file_get_contents('http://appoets.com/help.json');
            $Data = json_decode($str, true);
            return view('admin.help', compact('Data'));
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
    public function request_history() {

        try {

            $requests = UserRequests::RequestHistory()->get();

            return view('admin.request.request-history', compact('requests'));
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
    public function request_details($id) {
        try {
            $request = UserRequests::findOrFail($id);
            return view('admin.request.request-details', compact('request'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * User Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_review() {
        try {
            $Reviews = UserRequestRating::where('user_id', '!=', 0)->with('user', 'provider')->get();
            return view('admin.review.user_review', compact('Reviews'));
        } catch (Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * Provider Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_review() {
        try {
            $Reviews = UserRequestRating::where('provider_id', '!=', 0)->with('user', 'provider')->get();
            return view('admin.review.provider_review', compact('Reviews'));
        } catch (Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error', 'Something Went Wrong!');
        }
    }

    public function destory_allocation(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:providers,id',
            'service' => 'required|exists:service_types,id',
        ]);

        try {

            ProviderService::where('provider_id', $request->id)
                    ->where('service_type_id', $request->service)
                    ->delete();

            return back()->with('flash_success', 'Service Deleted');
        } catch (Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProviderService
     * @return \Illuminate\Http\Response
     */
    public function destory_provider_service($id) {

        try {
            ProviderService::find($id)->delete();
            return back()->with('message', 'Service deleted successfully');
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
    public function scheduled_request() {
        try {
            $requests = UserRequests::where('status', 'SCHEDULED')
                    ->RequestHistory()
                    ->get();

            return view('admin.request.scheduled-request', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * privacy.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function privacy() {
        return view('admin.pages.static')
                        ->with('title', "Privacy Page")
                        ->with('page', "privacy");
    }

    /**
     * pages.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request) {
        $this->validate($request, [
            'page' => 'required|in:page_privacy',
            'content' => 'required',
        ]);

        Setting::set($request->page, $request->content);
        Setting::save();

        return back()->with('flash_success', 'Content Updated!');
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($type = 'individual') {

        try {

            $page = 'Service Statement';

            if ($type == 'individual') {
                $page = 'Provider Service Statement';
            } elseif ($type == 'today') {
                $page = 'Today Statement - ' . date('d M Y');
            } elseif ($type == 'monthly') {
                $page = 'This Month Statement - ' . date('F');
            } elseif ($type == 'yearly') {
                $page = 'This Year Statement - ' . date('Y');
            }

            $rides = UserRequests::with('payment')->orderBy('id', 'desc');
            $cancel_rides = UserRequests::where('status', 'CANCELLED');
            $revenue = UserRequestPayment::select(\DB::raw(
                                    'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
            ));

            if ($type == 'today') {

                $rides->where('created_at', '>=', Carbon::today());
                $cancel_rides->where('created_at', '>=', Carbon::today());
                $revenue->where('created_at', '>=', Carbon::today());
            } elseif ($type == 'monthly') {

                $rides->where('created_at', '>=', Carbon::now()->month);
                $cancel_rides->where('created_at', '>=', Carbon::now()->month);
                $revenue->where('created_at', '>=', Carbon::now()->month);
            } elseif ($type == 'yearly') {

                $rides->where('created_at', '>=', Carbon::now()->year);
                $cancel_rides->where('created_at', '>=', Carbon::now()->year);
                $revenue->where('created_at', '>=', Carbon::now()->year);
            }

            $rides = $rides->get();
            $cancel_rides = $cancel_rides->count();
            $revenue = $revenue->get();

            return view('admin.providers.statement', compact('rides', 'cancel_rides', 'revenue'))
                            ->with('page', $page);
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * account statements today.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_today() {
        return $this->statement('today');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_monthly() {
        return $this->statement('monthly');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_yearly() {
        return $this->statement('yearly');
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_provider() {

        try {

            $Providers = Provider::all();

            foreach ($Providers as $index => $Provider) {

                $Rides = UserRequests::where('provider_id', $Provider->id)
                                ->where('status', '<>', 'CANCELLED')
                                ->get()->pluck('id');

                $Providers[$index]->rides_count = $Rides->count();

                $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                                'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
                                ))->get();
            }

            return view('admin.providers.provider-statement', compact('Providers'))->with('page', 'Providers Statement');
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
    public function translation() {

        try {
            return view('admin.translation');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * payouts statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function payouts() {

        try {
            $payouts = Payouts::with('provider')->orderBy('id', 'desc')->get();

            return view('admin.providers.provider-payouts', compact('payouts'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    public function transferlist(Request $request) {
        $Pendinglist = WalletRequest::select(['wallet_requests.*', 'providers.first_name as first_name', 'providers.last_name as last_name', 'providers.rib as rib'])
                ->leftJoin('providers', 'wallet_requests.from_id', '=', 'providers.id')
                ->where('wallet_requests.status', 'PENDING')
                ->where('wallet_requests.request_from', 'provider')
                ->orderBy('wallet_requests.created_at', 'desc')
                ->get();
        return view('admin.providers.provider-settlements', compact('Pendinglist'));
    }

    public function view_transfer() {
        return view('admin.providers.provider-settlement-create');
    }

    public function create_transfer(Request $request) {
        $this->validate($request, [
            'amount' => 'required|numeric|between:0,9999.99',
            'from_id' => 'required|integer',
            'type' => 'required|string',
            'send_by' => '',
        ]);

        // $ProviderWallet = ProviderWallet::select(DB::raw('SUM(amount) as total_amount'))
        //     ->where('provider_id', $request->from_id)
        //     ->get();
        // $WalletRequest = WalletRequest::select(DB::raw('SUM(amount) as total_amount'))
        //     ->where('from_id', $request->from_id)
        //     ->whereIn('status', ['APPROVED', 'PENDING'])
        //     ->get();
        // $amount = count($ProviderWallet) > 0 ? $ProviderWallet[0]->total_amount : 0;
        // $amount += count($WalletRequest) > 0 ? $WalletRequest[0]->total_amount : 0;
        // if ($amount <= 0) {
        //     return back()->with('flash_error', 'your wallet is ' . currency($amount));
        // } else {
        $wallet = new WalletRequest;
        $wallet->alias_id = 'PSET' . round(microtime(true) * 1000);
        $wallet->request_from = 'provider';
        $wallet->from_id = $request->from_id;
        $wallet->from_desc = $request->type . ' du règlement';
        $wallet->type = $request->type;
        $wallet->amount = $request->amount;
        $wallet->send_by = empty($request->send_by) ? 'NULL' : $request->send_by;
        $wallet->status = 'APPROVED';
        $wallet->save();

        return back()->with('flash_success', 'Settle Successfully');
        // return view('admin.providers.provider-settlement-create');
        // }
        // return view('admin.providers.provider-settlement-create');
    }

    public function search(Request $request) {
        $results = array();
        $term = $request->input('stext');
        $queries = Provider::where('first_name', 'LIKE', $term . '%')->take(5)->get();
        foreach ($queries as $query) {
            $ProviderWallet = ProviderWallet::select(DB::raw('SUM(amount) as total_amount'))
                    ->where('provider_id', $query->id)
                    ->get();
            $WalletRequest = WalletRequest::select(DB::raw('SUM(amount) as total_amount'))
                    ->where('from_id', $query->id)
                    ->whereIn('status', ['APPROVED', 'PENDING'])
                    ->get();
            $amount = count($ProviderWallet) > 0 ? $ProviderWallet[0]->total_amount : 0;
            $amount += count($WalletRequest) > 0 ? $WalletRequest[0]->total_amount : 0;
            $query->wallet_balance = currency($amount);
            $results[] = $query;
        }
        return response()->json(array('success' => true, 'data' => $results));
    }

    public function approve(Request $request, $id) {
        // if ($request->send_by == "online") {
        // 	$response = (new PaymentController)->send_money($request, $id);
        // } else {
        // 	(new TripController)->settlements($id);
        // 	$response['success'] = 'Amount successfully send';
        // }
        // if (!empty($response['error']))
        // 	$result['flash_error'] = $response['error'];
        // if (!empty($response['success']))
        // 	$result['flash_success'] = $response['success'];
        // return redirect()->back()->with($result);
        $result = (new TripController)->settlement_provider($id, $request->send_by);
        if ($result)
            return back()->with('flash_success', 'Amount successfully send');
        else
            return back()->with('flash_error', 'send failed');
    }

    public function requestcancel(Request $request) {
        // $cancel = (new TripController())->requestcancel($request);
        // $response = json_decode($cancel->getContent(), true);
        // if (!empty($response['error']))
        // 	$result['flash_error'] = $response['error'];
        // if (!empty($response['success']))
        // 	$result['flash_success'] = $response['success'];
        // return redirect()->back()->with($result);
        $this->validate($request, [
            'id' => 'required|integer',
        ]);
        $result = (new TripController)->cancel_settlement($request->id);
        if ($result)
            return back()->with('flash_success', 'cancel successfully');
        else
            return back()->with('flash_error', 'cancel failed');
    }

    public function transactions(Request $request) {
        $transactions = (new TripController)->getWalletHistory2();

        $total_amount = 0;
        foreach ($transactions as $transaction) {
            $total_amount += $transaction['total_amount'];
        }
        return view('admin.providers.provider-settlement-history', compact('transactions', 'total_amount'));
    }

    /**
     * show only the lastest 24h new provider and new user.
     *
     * @param  \App\Provider  $id
     * @return \Illuminate\Http\Response
     */
    public function latest_list($id) {
        try {
            if ($id == 1) {
                $title = "Nouveau Utilisateurs";
                $newUsers = User::where('created_at', '>=', Carbon::now()->subDay())->get();
                return view('admin.new_users', compact('newUsers', 'title'));
            } else {
                $title = "Nouveau Fournisseur";
                $providers = Provider::with('service', 'accepted', 'cancelled')
                        ->orderBy('id', 'DESC')
                        ->where('created_at', '>=', Carbon::now()->subDay())
                        ->get();

                return view('admin.providers.index', compact('providers'));
            }
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

}
