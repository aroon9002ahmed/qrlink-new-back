<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    protected $fillable = [
        'page_id',
        'visitor_hash',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
