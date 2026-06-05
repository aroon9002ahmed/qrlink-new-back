<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Template extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'page_type_id',
        'name',
        'slug',
        'description',
        'preview_image',
        'status',
    ];

    /**
     * العلاقة مع جدول PageTypes
     */
    public function pageType()
    {
        return $this->belongsTo(PageType::class);
    }

    /**
     * العلاقة مع الصفحات (Pages)
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
