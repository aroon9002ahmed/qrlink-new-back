<?php

namespace App\Models;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request; // Fix: Use the correct Request import
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RestaurantSettings;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'image_path',
        'type',
        'template_id',
        'settings',
        'language',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pageType(): BelongsTo
    {
        return $this->belongsTo(PageType::class, 'type');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class)->orderBy('position');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class)->orderBy('position');
    }

    public function restaurantCategories(): HasMany
    {
        return $this->hasMany(RestaurantMenuCategory::class)->orderBy('position');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RestaurantMenuItem::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(RestaurantOrder::class);
    }
    // public function getTablesOrdersCountAttribute(): int
    // {
    //     return $this->orders()->where('type', 'table')->count();
    // }
    // public function getDeliveryOrdersCountAttribute(): int
    // {
    //     return $this->orders()->where('type', 'delivery')->count();
    // }
    // public function getTakeawayOrdersCountAttribute(): int
    // {
    //     return $this->orders()->where('type', 'takeaway')->count();
    // }



    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function addView(Request $request): void
    {
        $visitorHash = hash('sha256', $request->ip() . $request->userAgent());

        PageView::firstOrCreate([
            'page_id' => $this->id,
            'visitor_hash' => $visitorHash,
        ], [
            'viewed_at' => now(),
        ]);
    }

    public function getUniqueViewsCountAttribute(): int
    {
        return $this->pageViews()->count();
    }

    public function restaurantSettings(): HasOne
    {
        return $this->hasOne(RestaurantSettings::class);
    }

    public function getOrCreateRestaurantSettings(): RestaurantSettings
    {
        return $this->restaurantSettings()->firstOrCreate([
            'page_id' => $this->id,
        ], [
            'currency' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'before',
            'enable_orders' => true,
        ]);
    }

    public function formatRestaurantPrice(float $price): string
    {
        $settings = $this->restaurantSettings;

        if (!$settings) {
            return '$' . number_format($price, 2);
        }

        return $settings->formatPrice($price);
    }
}
