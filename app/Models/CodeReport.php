<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodeReport extends Model
{
    protected $fillable = [
        'short_code_id',
        'ip_address',
        'reason',
    ];

    /**
     * Get the short code that was reported.
     */
    public function shortCode(): BelongsTo
    {
        return $this->belongsTo(ShortCode::class);
    }
}
