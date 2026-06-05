<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'platform_id', // Correct column name from migration
        'value',
        'sort_order'  // Add this
    ];

    // Add default ordering
    protected static function boot()
    {
        parent::boot();

        // Auto-assign sort_order when creating
        static::creating(function ($socialLink) {
            if (is_null($socialLink->sort_order)) {
                $maxOrder = static::where('page_id', $socialLink->page_id)->max('sort_order') ?? 0;
                $socialLink->sort_order = $maxOrder + 1;
            }
        });
    }

    /**
     * Link belongs to platform.
     */
    public function socialPlatform()
    {
        return $this->belongsTo(SocialPlatform::class, 'platform_id'); // Adjust foreign key if needed
    }

    /**
     * ارجاع الرابط النهائي الجاهز للاستخدام
     */
    public function getFullUrlAttribute()
    {
        $platform = $this->platform;

        if (!$platform) {
            return $this->value;
        }

        switch ($platform->type) {
            case 'link':
                return $platform->base_url . $this->value;

            case 'phone':
                return 'tel:' . $this->value;

            case 'email':
                return 'mailto:' . $this->value;

            case 'whatsapp':
                return 'https://wa.me/' . $this->value;

            default:
                return $this->value;
        }
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Scope for ordered links
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
