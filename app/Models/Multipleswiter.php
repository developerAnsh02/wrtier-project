<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multipleswiter extends Model
{
    use HasFactory;
    protected $table = 'multiple_wrtier';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
