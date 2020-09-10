<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderEmails extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'email_subject',
        'email_body'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function provider() {
        return $this->belongsTo('App\Provider');
    }

}
