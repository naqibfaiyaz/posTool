<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class catalog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $casts = [ 'category_id' => 'integer', 'price' => 'float', 'discount_status' => 'integer', 'status' => 'integer' ];
     
    protected $fillable = [
        'name', 'category_id', 'image', 'price', 'discount_status', 'status'
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
        return $this->belongsTo('App\catalogCategory', 'category_id');
    }

    public function quantity()
    {
        return $this->hasOne('App\catalogQuantity', 'catalog_id');
    }
}
