<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantShiftHandover extends Model
{
    use HasFactory;

    protected $table = 'restaurant_shift_handovers';

    protected $fillable = [
        'page_id',
        'cashier_name',
        'opening_cash',
        'system_sales',
        'total_cash',
        'next_opening_cash',
        'cash_difference',
        'notes',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
