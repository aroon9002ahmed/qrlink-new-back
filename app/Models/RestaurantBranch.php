<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantBranch extends Model
{
    protected $fillable = [
        'page_id',
        'name',
        'address',
        'image',
        'latitude',
        'longitude',
        'main',
        'status',
    ];
}
