<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ShortCode extends Model
{
    protected $fillable = [
        'code',
        'codeable_id',
        'codeable_type',
        'user_id',
        'clicks',
    ];

    /**
     * Polymorphic relation: belongs to a Link or Qrcode.
     */
    public function codeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns this short code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reports for this short code.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(CodeReport::class);
    }

    /**
     * Generate a globally unique short code (checks the short_codes table only).
     */
    public static function generateUnique(int $length = 8): string
    {
        do {
            $code = Str::random($length);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
