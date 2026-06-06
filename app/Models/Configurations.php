<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configurations extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'note',
        'status',
        'inputType',
        'created_by'
    ];
    public $translatable  = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($config) {
            if (empty($config->created_by)) {
                $config->created_by = auth('admin')->id() ?? auth()->id() ?? 1;
            }
        });
    }

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
