<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'old_path',
        'new_path',
        'status_code',
        'is_active',
        'hits',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status_code' => 'integer',
        'hits' => 'integer',
    ];
}
