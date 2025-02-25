<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    public function writer()
    {
        return $this->belongsTo(User::class, 'wid');
    }

    public function subwriter()
    {
        return $this->belongsTo(User::class, 'swid');
    }

    public function mulsubwriter()
    {
        return $this->hasMany(Multipleswiter::class, 'order_id', 'id')->with(['user' => function ($query) {
            $query->select('id', 'name');
        }]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

  
    
      public function order()
    {
        return $this->belongsTo(multipleswiter::class, 'order_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'order_id', 'id');

    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


   

}
