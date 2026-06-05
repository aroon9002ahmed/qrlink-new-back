<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirections extends Model
{
    protected $table = 'redirects';

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
