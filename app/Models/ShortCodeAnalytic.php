<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortCodeAnalytic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_code_analytics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'short_code_id',
        'ip_address',
        'user_agent',
        'country',
        'city',
    ];

    /**
     * Get the short code that owns these analytics.
     */
    public function shortCode(): BelongsTo
    {
        return $this->belongsTo(ShortCode::class);
    }
}
