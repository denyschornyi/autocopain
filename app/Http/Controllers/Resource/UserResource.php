<?php

namespace App\Http\Controllers\Resource;

use App\User;
use App\UserRequests;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Storage;
use Setting;
use App\UserEmails;
use Mail;
use Illuminate\Support\Facades\Config;

class UserResource extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'mobile' => '',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

            $user = $request->all();

            $user['payment_mode'] = 'CASH';
            $user['password'] = bcrypt($request->password);
            if ($request->hasFile('picture')) {
                $user['picture'] = $request->picture->store('user/profile');
            }

            $user = User::create($user);

            return back()->with('flash_success', 'User Details Saved Successfully');
        } catch (Exception $e) {
            return back()->with('flash_errors', 'User Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.user-details', compact('user'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.edit', compact('user'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

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

            $user = User::findOrFail($id);

            if ($request->hasFile('picture')) {
                $user->picture = $request->picture->store('user/profile');
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile = $request->mobile;
            $user->save();

            return redirect()->route('admin.user.index')->with('flash_success', 'User Updated Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'User Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            User::find($id)->delete();
            return back()->with('message', 'User deleted successfully');
        } catch (Exception $e) {
            return back()->with('flash_errors', 'User Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id) {

        try {

            $requests = UserRequests::where('user_requests.user_id', $id)
                    ->RequestHistory()
                    ->get();

            return view('admin.request.request-history', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

    /**
     * send email to user form.
     *
     * @param  \App\Provider  $user
     * @return \Illuminate\Http\Response
     */
    public function send_email($id) {
        $User = $id;
        return view('admin.users.send_emails', compact('User'));
    }

    //    func to send emails to user
    public function send_user_email(Request $request) {
        $request->email_body = str_replace('"../../asset/', '"https://autocopain.com/asset/', $request->email_body);
        $emailContents = array(
            'subject' => $request->email_subject,
            'body' => $request->email_body
        );

        try {
            $user = User::findOrFail($request->userId);

            $this->saveHistory($request, $request->userId);

            $status = $this->SendEmail($user->email, $emailContents, 'emails.blukemail');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'User Not Found');
        }
        if ($status == 1) {
            return back()->with('flash_success', 'Emails Sent Successfully');
        } else {
            return back()->with('flash_error', 'Something Went Wrong. Emails Not Sent');
        }
    }

    //    send email function
    public function SendEmail($EmailTo, $emailContents, $body) {
        try {
            Config::set('mail.username', 'support@autocopain.fr');
            Config::set('mail.password', 'Editionsp39**');
            $data = array('email' => "umairm638@gmail.com", 'subject' => $emailContents['subject'], 'emailContents' => $emailContents);
            Mail::send(['html' => $body], $data, function ($message) use ($data) {
                $message->from('support@autocopain.fr', 'Support AutoCopain');
                $message->to($data['email']);
                $message->subject($data['subject']);
            });
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function saveHistory($request, $user_id) {
        $email = new UserEmails;
        $email->user_id = $user_id;
        $email->email_subject = trim($request->email_subject);
        $email->email_body = $request->email_body;
        $email->save();
        return 1;
    }

    public function email_history($id) {
        try {

            $history = UserEmails::where('user_id', $id)->get();

            return view('admin.users.email_history', compact('history'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong!');
        }
    }

}
