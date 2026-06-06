<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faqs extends Model
{
    use HasFactory;
    use HasTranslations;
    public $translatable = ['question','answer'];

    protected $fillable = [
        'question',
        'answer',
        'status',
        'order',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($faq) {
            if (empty($faq->created_by)) {
                $faq->created_by = auth('admin')->id() ?? auth()->id() ?? 1;
            }
        });
    }


    public function Admin(){
        return $this->belongsTo(Admin::class,'created_by');
    }
}
