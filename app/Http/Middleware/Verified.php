<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Verified
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isVerified() == 1) {
            return $next($request);
        }
        if (Auth::user()->isVerified() == 0) {
            Auth::logout();
            \Session::flash('message',
                'Your email is not verified. Please check your mailbox for confirmation or contact system administrator.');
            return redirect('/login');
        }
        return redirect('/logout');
    }
}
