<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantMenuCategory extends Model
{
    use HasFactory;

    protected $fillable = ['page_id', 'title', 'position', 'settings'];

    protected $casts = [
        'settings' => 'array'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function items()
    {
        return $this->hasMany(RestaurantMenuItem::class, 'category_id')->orderBy('position');
    }

    // Helper method to get setting with default
    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    // Helper method to check if orders are enabled
    public function ordersEnabled()
    {
        return $this->getSetting('enable_orders', true);
    }

    // Helper method to check if images should be shown
    public function showImages()
    {
        return $this->getSetting('show_images', true);
    }

    // Helper method to check if prices should be shown
    public function showPrices()
    {
        return $this->getSetting('show_prices', true);
    }

    // Helper method to get display style
    public function getDisplayStyle()
    {
        return $this->getSetting('display_style', 'cards'); // cards, list, grid
    }
}
