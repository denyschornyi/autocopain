<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderWallet extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'transaction_id',
        'transaction_alias',
        'transaction_desc',
        'amount',
        'open_balance',
        'close_balance',
        'payment_mode', 
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'updated_at'
    ];

    
}
