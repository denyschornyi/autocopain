<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'picture', 'password', 'device_type', 'device_token', 'login_by', 'payment_mode', 'social_unique_id', 'device_id', 'wallet_balance', 'token', 'isVerified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function isVerified() {
        return $this->isVerified; // this looks for an isVerified column in your users table
    }

    public static function getEmailOfAllActiveUsers()
    {
        return DB::table('users')
            ->select('email')
            ->where('isVerified', '=', 1)
            ->get();

    }
    public static function getEmailHistory()
    {
        return DB::table('email_history')
            ->orderBy('created_at', 'desc')
            ->get();
    }

}
