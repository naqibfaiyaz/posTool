<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventory_identity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [ 'catalog_id'=>'integer', 'quantity' => 'integer'];
    
    protected $fillable = [
        'inventory_name', 'catalog_id', 'quantity'
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
