<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageType extends Model
{
    use HasFactory,HasTranslations;

    protected $fillable = [
        'name',        // اسم الـ Page type (مثلاً: Profile, Restaurant, Event, Product)
        'slug',        // slug للاستخدام في الurls أو الربط
        'description', // وصف مختصر لنوع الصفحة
        'icon',        // أيقونة اختيارية (لو حابب تعرضها في الـ dashboard)
        'status',      // الحالة (نشط أم غير نشط)
    ];

    public $translatable = ['name', 'description'];

    protected static function booted()
    {
        static::deleting(function ($pageType) {
            if ($pageType->icon) {
                $path = str_starts_with($pageType->icon, 'images/pageTypes/cache/')
                    ? $pageType->icon
                    : "images/pageTypes/cache/{$pageType->icon}";
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        });
    }

    /*
     * العلاقات
     */

    // كل PageType ممكن يكون ليه صفحات (Pages)
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // كل PageType ممكن يكون ليه قوالب جاهزة (Templates)
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function blockTypes()
    {
        return $this->belongsToMany(BlockType::class, 'page_type_block');
    }
}
