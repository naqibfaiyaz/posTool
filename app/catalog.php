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
    protected $fillable = [
        'name', 'category_id', 'image', 'price'
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
}
