<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['page_id', 'block_type_id', 'settings', 'position'];

    protected $casts = [
        'settings' => 'array', // نخزن JSON كـ array
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function blockType()
    {
        return $this->belongsTo(BlockType::class);
    }

    public function pageTypes()
    {
        return $this->belongsToMany(PageType::class, 'page_type_block');
    }

    // ADD THIS MISSING SCOPE METHOD
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }
}
