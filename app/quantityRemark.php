<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quantityRemark extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'quantity_id', 'quantity_reduced', 'remarks', 'user'
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
