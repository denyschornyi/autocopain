<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Provider;
Use Redirect;

class TokenController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyemail($token) {
        $error = 0;
        //check if token is valid
        $user = User::wheretoken($token)->first();
        if (count($user) > 0 && $user->isVerified == 0) {
            $user->fill([
                'isVerified' => 1
            ])->save();
            Auth::loginUsingId($user->id);
//            $error = 1;
//            return redirect('/dashboard');
        }
//        if ($error == 0) {
//            return redirect('/login');
//        }
        return Redirect::away('https://autocopain.fr/bravo');
    }

    public function providerVerifyemail($token) {
        $error = 0;
        //check if token is valid
        $user = Provider::wheretoken($token)->first();
        if (count($user) > 0 && $user->isVerified == 0) {
            $user->fill([
                'isVerified' => 1
            ])->save();
            Auth::loginUsingId($user->id);
//            $error = 1;
//            return redirect('/provider');
        }
//        if ($error == 0) {
//            return Redirect::away('https://autocopain.fr/bravo');
//            return redirect('/provider/login');
//        }
        return Redirect::away('https://autocopain.fr/bravo');
    }

}
