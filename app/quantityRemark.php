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
        'quantity_id', 'modified_quantity', 'remarks', 'user', 'input_type', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    public function quantityInv()
    {
        return $this->belongsTo('App\catalogQuantity', 'quantity_id');
    }
}
