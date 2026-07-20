<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'features', 'limitations'];
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'features',
        'max_links',
        'max_qrcodes',
        'max_pages',
        'max_items',
        'max_blocks_per_page',
        'custom_domain',
        'customization_templates',
        'restaurant_table',
        'delivery',
        'takeaway',
        'banners',
        'branches',
        'qr_code',
        'turn_off_Branding',
        'fast_redirect',
        'analytics',
        'priority_support',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'custom_domain' => 'boolean',
            'customization_templates' => 'boolean',
            'restaurant_table' => 'boolean',
            'delivery' => 'boolean',
            'takeaway' => 'boolean',
            'banners' => 'boolean',
            'branches' => 'boolean',
            'qr_code' => 'boolean',
            'turn_off_Branding' => 'boolean',
            'fast_redirect' => 'boolean',
            'analytics' => 'boolean',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_subscription_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function getFormattedPriceMonthlyAttribute(): string
    {
        return number_format($this->price_monthly, 2);
    }

    public function getFormattedPriceYearlyAttribute(): string
    {
        return number_format($this->price_yearly, 2);
    }

    public function getYearlySavingsAttribute(): float
    {
        $yearlyEquivalent = $this->price_monthly * 12;
        return $yearlyEquivalent - $this->price_yearly;
    }

    public function isFree(): bool
    {
        return $this->price_monthly == 0 && $this->price_yearly == 0;
    }
}
