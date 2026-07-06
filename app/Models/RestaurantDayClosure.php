<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantDayClosure extends Model
{
    use HasFactory;

    protected $table = 'restaurant_day_closures';

    protected $fillable = [
        'page_id',
        'user_id',
        'manager_name',
        'total_orders',
        'total_sales',
        'cash_sales',
        'card_sales',
        'notes',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
