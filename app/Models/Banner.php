<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page;

class Banner extends Model
{
    protected $fillable = [
        'page_id',
        'title',
        'link',
        'image',
        'status',
        'position',
        'end_date',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
