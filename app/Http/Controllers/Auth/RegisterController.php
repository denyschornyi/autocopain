<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use Illuminate\Http\Request;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $token = time();
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 20; $i++) {
            $token .= $characters[mt_rand(0, $max)];
        }
        $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'payment_mode' => 'CASH',
                    'token' => $token,
                    'isVerified' => 0,
        ]);

        // send welcome email here
        $userdata = [
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'token' => $token,
        ];
        $record = ['data' => $userdata,
            'email' => $data['email'],
            'subject' => 'Confirmez votre adresse mail',
        ];
        Mail::send('emails.verificationEmail', $record, function ($message) use ($record) {
            $message->from(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
            $message->to($record['email']);
            $message->replyTo(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
            $message->subject($record['subject']);
        });
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm() {
        return view('user.auth.register');
    }

    public function Register(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            $this->create($request->all());
        }
        $msg = 'Votre demande a bien été soumise, veuillez vérifier votre email pour activer votre compte';
        \Session::flash('message', $msg);
        return redirect('/login');
    }

}
