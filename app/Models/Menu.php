<?php

namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Menu extends Model
{
    use HasFactory;
    use NodeTrait;
    use HasTranslations;
    protected $table = 'menu';
    public $translatable = ['title'];

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function Location()
    {
        return $this->belongsTo(MenuLocations::class, 'location');
    }

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')->orderBy('order_view');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Menu', 'id', 'parent_id')->orderBy('order_view');
    }
}
