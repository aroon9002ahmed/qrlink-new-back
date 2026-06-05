<?php

namespace App\Models;

use App\Traits\HasShortCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qrcode extends Model
{
    use HasShortCode;

    protected $fillable = [
        'title',
        'original_url',
        'is_active',
        'expires_at',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
