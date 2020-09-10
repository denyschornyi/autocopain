<?php

namespace App;

use App\Notifications\ProviderResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Provider extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'address',
        'picture',
        'gender',
        'latitude',
        'longitude',
        'status',
        'description',
        'token',
        'isVerified',
        'stripe_cust_id',
        'wallet_balance',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at'
    ];

    /**
     * get all active providres.
     */
    public static function getEmailOfAllActiveProvers() {
        return DB::table('providers')
                        ->select('email')
                        ->where('isVerified', '=', 1)
                        ->get();
    }

    /**
     * The services that belong to the user.
     */
    public function service() {
        return $this->hasOne('App\ProviderService');
    }

    /**
     * The services that belong to the user.
     */
    public function incoming_requests() {
        return $this->hasMany('App\RequestFilter')->where('status', 0);
    }

    /**
     * The services that belong to the user.
     */
    public function requests() {
        return $this->hasMany('App\RequestFilter');
    }

    /**
     * The services that belong to the user.
     */
    public function profile() {
        return $this->hasOne('App\ProviderProfile');
    }

    /**
     * The services that belong to the user.
     */
    public function device() {
        return $this->hasOne('App\ProviderDevice');
    }

    /**
     * The services that belong to the user.
     */
    public function trips() {
        return $this->hasMany('App\UserRequests');
    }

    /**
     * The services accepted by the provider
     */
    public function accepted() {
        return $this->hasMany('App\UserRequests', 'provider_id')
                        ->where('status', '!=', 'CANCELLED');
    }

    /**
     * service cancelled by provider.
     */
    public function cancelled() {
        return $this->hasMany('App\UserRequests', 'provider_id')
                        ->where('status', 'CANCELLED');
    }

    /**
     * service cancelled by provider.
     */
    public function cancelled_count() {
        return $this->hasMany('App\UserRequests', 'provider_id')
                        ->where('status', 'CANCELLED')->where('cancelled_by', 'PROVIDER');
    }

    /**
     * The services that belong to the user.
     */
    public function documents() {
        return $this->hasMany('App\ProviderDocument');
    }

    /**
     * The services that belong to the user.
     */
    public function document($id) {
        return $this->hasOne('App\ProviderDocument')->where('document_id', $id)->first();
    }

    /**
     * The services that belong to the user.
     */
    public function pending_documents() {
        return $this->hasMany('App\ProviderDocument')->where('status', 'ASSESSING')->count();
    }

    public function no_documents() {
        return $this->hasMany('App\ProviderDocument')->count();
    }

    public function need_validate_documents() {
        return $this->hasMany('App\ProviderDocument')->where('verification_status', 0)->count();
    }

    public function validated_documents() {
        return $this->hasMany('App\ProviderDocument')->where('verification_status', 1)->count();
    }

    public function rejected_documents() {
        return $this->hasMany('App\ProviderDocument')->where('verification_status', 2)->count();
    }

    public function payouts() {
        return $this->hasMany('App\Payouts');
    }

    public function isVerified() {
        return $this->isVerified; // this looks for an isVerified column in your users table
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ProviderResetPassword($token));
    }

}
