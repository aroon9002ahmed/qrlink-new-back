<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockType extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['name','description'];

    protected $fillable = [
        'name',
        'description',
        'schema'
    ];

    protected $casts = [
        'schema' => 'array'
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function pageTypes()
    {
        return $this->belongsToMany(PageType::class, 'page_type_block');
    }
}
