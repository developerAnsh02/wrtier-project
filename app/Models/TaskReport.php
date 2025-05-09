<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_words',
        'task_date',
        'is_draft',
        'is_hidden_from_writer',
        'tasks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
