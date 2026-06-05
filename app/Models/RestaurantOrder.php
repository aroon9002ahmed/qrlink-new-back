<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantOrder extends Model
{
    use HasFactory;

    protected $fillable = ['page_id', 'table_id', 'type', 'customer_name', 'customer_phone', 'customer_address', 'branch_id', 'status', 'total_price'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function items()
    {
        return $this->hasMany(RestaurantOrderItem::class, 'order_id');
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class)->where('type', 'table');
    }

    public function branch()
    {
        return $this->belongsTo(RestaurantBranch::class);
    }
}
