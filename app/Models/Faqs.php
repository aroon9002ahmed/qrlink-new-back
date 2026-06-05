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


    public function Admin(){
        return $this->belongsTo(Admin::class,'created_by');
    }
}
