<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'currency',
        'currency_symbol',
        'currency_position',
        'opening_hours',
        'restaurant_phone',
        'restaurant_address',
        'enable_orders',
        'enable_tables',
        'enable_delivery',
        'enable_takeaway',
        'hotline',
        'whatsapp_number',
        'receive_orders_on_whatsapp',
    ];

    protected function casts(): array
    {
        return [
            'enable_orders' => 'boolean',
            'enable_tables' => 'boolean',
            'enable_delivery' => 'boolean',
            'enable_takeaway' => 'boolean',
            'receive_orders_on_whatsapp' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function formatPrice(float $price): string
    {
        $formattedPrice = number_format($price, 2);

        // Handle Arabic and RTL currencies specially
        $arabicCurrencies = ['ج.م', 'ر.س', 'د.إ'];
        $isArabicCurrency = in_array($this->currency_symbol, $arabicCurrencies);

        if ($this->currency_position === 'before') {
            if ($isArabicCurrency) {
                // For Arabic currencies, add a space for better readability
                return $this->currency_symbol . ' ' . $formattedPrice;
            }
            return $this->currency_symbol . $formattedPrice;
        }

        // Position after
        if ($isArabicCurrency) {
            // For Arabic currencies when positioned after, maintain proper spacing
            return $formattedPrice . ' ' . $this->currency_symbol;
        }

        return $formattedPrice . ' ' . $this->currency_symbol;
    }

    public static function getAvailableCurrencies(): array
    {
        return [
            'USD' => ['symbol' => '$', 'name' => 'US Dollar'],
            'EUR' => ['symbol' => '€', 'name' => 'Euro'],
            'GBP' => ['symbol' => '£', 'name' => 'British Pound'],
            'JPY' => ['symbol' => '¥', 'name' => 'Japanese Yen'],
            'CAD' => ['symbol' => 'C$', 'name' => 'Canadian Dollar'],
            'AUD' => ['symbol' => 'A$', 'name' => 'Australian Dollar'],
            'CHF' => ['symbol' => 'CHF', 'name' => 'Swiss Franc'],
            'CNY' => ['symbol' => '¥', 'name' => 'Chinese Yuan'],
            'SEK' => ['symbol' => 'kr', 'name' => 'Swedish Krona'],
            'NZD' => ['symbol' => 'NZ$', 'name' => 'New Zealand Dollar'],
            'MXN' => ['symbol' => '$', 'name' => 'Mexican Peso'],
            'SGD' => ['symbol' => 'S$', 'name' => 'Singapore Dollar'],
            'HKD' => ['symbol' => 'HK$', 'name' => 'Hong Kong Dollar'],
            'NOK' => ['symbol' => 'kr', 'name' => 'Norwegian Krone'],
            'INR' => ['symbol' => '₹', 'name' => 'Indian Rupee'],
            'BRL' => ['symbol' => 'R$', 'name' => 'Brazilian Real'],
            'KRW' => ['symbol' => '₩', 'name' => 'South Korean Won'],
            'SAR' => ['symbol' => 'ر.س', 'name' => 'Saudi Riyal'],
            'AED' => ['symbol' => 'د.إ', 'name' => 'UAE Dirham'],
            'EGP' => ['symbol' => 'ج.م', 'name' => 'Egyptian Pound'],
        ];
    }
}
