<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletRequest extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias_id',
        'request_from',
        'from_id',
        'from_desc',
        'type',
        'amount',
        'send_by',
        'send_desc',
        'status',
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
