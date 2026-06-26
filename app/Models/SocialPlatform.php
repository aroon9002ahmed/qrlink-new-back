<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'base_url',
        'color',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Platform has many links.
     */
    public function links()
    {
        return $this->hasMany(SocialLink::class, 'platform_id');
    }
}
