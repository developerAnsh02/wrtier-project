<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenus extends Model
{
    use HasFactory;
    protected $table = 'submenus';

    public function menu()
    {
        return $this->belongsTo(menu::class, 'menus_id');

    }

}
