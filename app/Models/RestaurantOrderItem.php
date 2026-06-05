<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'menu_item_id', 'variation_name', 'variation_price', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(RestaurantOrder::class, 'order_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(RestaurantMenuItem::class, 'menu_item_id');
    }

    public function extras()
    {
        return $this->hasMany(RestaurantOrderItemExtra::class, 'order_item_id');
    }
}
