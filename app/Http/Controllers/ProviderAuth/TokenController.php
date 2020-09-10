<?php

namespace App\Http\Controllers\ProviderAuth;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Notifications\ResetPasswordOTP;
use Auth;
use Config;
use Setting;
use JWTAuth;
use Exception;
use Notification;
use App\Provider;
use App\ProviderDevice;
use Mail;

class TokenController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $this->validate($request, [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:providers',
            'mobile' => 'required',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'required',
            'avatar.*' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        try {

            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $token = time();
            $max = strlen($characters) - 1;
            for ($i = 0; $i < 20; $i++) {
                $token .= $characters[mt_rand(0, $max)];
            }
            $Provider = $request->all();
            $Provider['password'] = bcrypt($request->password);
            $Provider['status'] = 'banned';
            $Provider['token'] = $token;
            $Provider['isVerified'] = 0;

            if ($request->hasFile('avatar')) {
                foreach($request->file('avatar') as $image)
                {
                    $url = $image->store('provider/profile');
                    $Provider['avatar'] = $url;
                }
            }

            $Provider = Provider::create($Provider);

            ProviderDevice::create([
                'provider_id' => $Provider->id,
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);

            // send welcome email here
            $userdata = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'token' => $token,
            ];
            $record = ['data' => $userdata,
                'email' => $request->email,
                'subject' => 'Confirmez votre adresse mail',
            ];
            Mail::send('emails.providerVerificationEmail', $record, function ($message) use ($record) {
                $message->from(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
                $message->to($record['email']);
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
                $message->subject($record['subject']);
            });
            return response()->json([
                        'message' => 'Un mail de vérification vous a été envoyé par mail!',
                        'user' => $Provider
            ]);

//            return $Provider;
        } catch (QueryException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Quelque chose c\'est mal passé. Merci d\'essayer plus tard!'], 500);
            }
            return abort(500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {
//        $this->validate($request, [
//            'device_id' => 'required',
//            'device_type' => 'required|in:android,ios',
//            'device_token' => 'required',
//            'email' => 'required|email',
//            'password' => 'required|min:6',
//        ]);

        Config::set('auth.providers.users.model', 'App\Provider');

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'L\'adresse email ou le mot de passe que vous avez entré est incorrect.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Quelque chose c\'est mal passé. Merci d\'essayer plus tard!'], 500);
        }

        $User = Provider::with('service', 'device')->find(Auth::user()->id);

        if ($User->isVerified == 0) {
            return response()->json(['error' => 'Vous devez vérifier votre mail d\'abord.'], 401);
        }

        $User->access_token = $token;
        $User->currency = Setting::get('currency', '$');

        if ($User->device) {
            if ($User->device->token != $request->token) {
                $User->device->update([
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
            }
        } else {
            ProviderDevice::create([
                'provider_id' => $User->id,
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
        }

        return response()->json($User);
    }

//    public function authenticate1(Request $request) {
//        Config::set('auth.providers.users.model', 'App\Provider');
//
//        $credentials = $request->only('email', 'password');
//
//        try {
//            if (!$token = JWTAuth::attempt($credentials)) {
//                return response()->json(['error' => 'L\'adresse email ou le mot de passe que vous avez entré est incorrect.'], 401);
//            }
//        } catch (JWTException $e) {
//            return response()->json(['error' => 'Quelque chose c\'est mal passé. Merci d\'essayer plus tard!'], 500);
//        }
//
//        $User = Provider::with('service', 'device')->find(Auth::user()->id);
//
//        if ($User->isVerified == 0) {
//            return response()->json(['error' => 'You must verify your email first.'], 401);
//        }
//
//        $User->access_token = $token;
//        $User->currency = Setting::get('currency', '$');
//
//        if ($User->device) {
//            if ($User->device->token != $request->token) {
//                $User->device->update([
//                    'udid' => $request->device_id,
//                    'token' => $request->device_token,
//                    'type' => $request->device_type,
//                ]);
//            }
//        } else {
//            ProviderDevice::create([
//                'provider_id' => $User->id,
//                'udid' => $request->device_id,
//                'token' => $request->device_token,
//                'type' => $request->device_type,
//            ]);
//        }
//
//        return response()->json($User);
//    }

    public function refreshtoken(Request $request) {
        $token = $request->bearerToken();
        try {
            if (! $new_token = JWTAuth::refresh($token)) {
                return response()->json(['user_not_found'], 404);
            } else {
                return response()->json(['new_token' => $new_token]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'refresh_error'], $e->getStatusCode());
        }
        return response()->json(['error' => 'invalided_token'], 401);
    }

    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request) {

        $this->validate($request, [
            'email' => 'required|email|exists:providers,email',
        ]);

        try {

            $provider = Provider::where('email', $request->email)->first();

            $otp = mt_rand(100000, 999999);

            $provider->otp = $otp;
            $provider->save();

            Notification::send($provider, new ResetPasswordOTP($otp));

            return response()->json([
                        'message' => 'Un code de récupération vous a été envoyé par mail!',
                        'provider' => $provider
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function reset_password(Request $request) {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'id' => 'required|numeric|exists:providers,id'
        ]);

        try {

            $Provider = Provider::findOrFail($request->id);
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            if ($request->ajax()) {
                return response()->json(['message' => 'Mot de passe mis à jour']);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        try {
            ProviderDevice::where('provider_id', $request->id)->update(['udid' => '', 'token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

}
