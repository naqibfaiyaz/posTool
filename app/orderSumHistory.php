<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderSumHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'token_no', 'order_time', 'customer_type', 'seller_name', 'subtotal', 'Total_discount', 'total_price', 'cash_tendered', 'change_due', 'order_status'
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
