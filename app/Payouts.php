<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payouts extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'rib',
        'troubleShooting',
        'cashReceived',
        'cb',
        'commission',
        'result'
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
