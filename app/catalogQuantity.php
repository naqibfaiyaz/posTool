<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class catalogQuantity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catalog_id', 'quantity'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function catalog()
    {
        return $this->belongsTo('App\catalog', 'catalog_id');
    }

    public function remarks()
    {
        return $this->hasMany('App\quantityRemark', 'quantity_id');
    }
}
