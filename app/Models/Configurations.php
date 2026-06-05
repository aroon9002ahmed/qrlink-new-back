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
        'created_by'
    ];
    public $translatable  = ['name'];

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
