<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'token_no', 'item_name', 'item_quantity', 'item_price', 'item_discount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
