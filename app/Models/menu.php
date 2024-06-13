<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;
    protected $table = 'menu';

    public function Submenus()
    {
        return $this->hasMany(Submenus::class, 'menus_id');
    }
}
