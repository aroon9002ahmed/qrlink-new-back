<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantMenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'page_id', 'name', 'description', 'image', 'price', 'is_available', 'position', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(RestaurantMenuCategory::class, 'category_id');
    }

    // Helper method to check if item has image
    public function hasImage()
    {
        return !empty($this->image);
    }

    // Helper method to get image URL
    public function getImageUrl()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    // Accessor for image URL
    // public function getImageUrlAttribute()
    // {
    //     return $this->image ? Storage::url($this->image) : null;
    // }

    public function variations()
    {
        return $this->hasMany(RestaurantMenuItemVariation::class, 'menu_item_id')->orderBy('position');
    }

    public function extras()
    {
        return $this->hasMany(RestaurantMenuItemExtra::class, 'menu_item_id')->orderBy('position');
    }
}
