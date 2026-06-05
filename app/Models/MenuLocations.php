<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MenuLocations extends Model
{
    use HasTranslations;
    public $translatable = ['title'];

    public function Admin(){
        return $this->belongsTo(Admin::class,'created_by');
    }
}
