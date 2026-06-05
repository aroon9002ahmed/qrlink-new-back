<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainPage extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'body', 'meta_title', 'meta_description', 'meta_keywords'];

    public function Admin(){
        return $this->belongsTo(Admin::class,'created_by');
    }
}
