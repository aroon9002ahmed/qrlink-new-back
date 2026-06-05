<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantMenuItemExtra extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id', 'name', 'price', 'is_available'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function menuItem()
    {
        return $this->belongsTo(RestaurantMenuItem::class, 'menu_item_id');
    }
}
