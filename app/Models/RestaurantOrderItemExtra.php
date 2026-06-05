<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantOrderItemExtra extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'name', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(RestaurantOrderItem::class, 'order_item_id');
    }
}
