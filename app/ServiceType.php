<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'category_id', 'name', 'provider_name', 'image', 'fixed', 'price', 'description', 'status', 'notice'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
