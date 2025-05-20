<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multipleswiter extends Model
{
    use HasFactory;
    protected $table = 'multiple_wrtier';
    protected $fillable = ['user_id', 'order_id', 'word_count'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
