<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'page_id',
        'table_number',
        'seating_capacity',
        'type',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(RestaurantOrder::class, 'table_id');
    }
}
