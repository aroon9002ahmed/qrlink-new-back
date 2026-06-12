<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'reason',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blacklist) {
            if (empty($blacklist->created_by)) {
                $blacklist->created_by = auth('admin')->id() ?? auth()->id() ?? 1;
            }
        });
    }

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
