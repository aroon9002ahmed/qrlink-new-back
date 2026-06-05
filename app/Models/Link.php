<?php

namespace App\Models;

use App\Traits\HasShortCode;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasShortCode;

    protected $fillable = [
        'original_url',
        'url_hash',
        'is_active',
        'expires_at',
        'user_id',
        'title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
