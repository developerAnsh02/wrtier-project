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
        'tasks',
        'task_date',
        'is_draft',
        'submitted_at',
        'is_hidden_from_writer',
        'version',
        'parent_id',
        'edit_request_status',
        'edit_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getTasksArrayAttribute()
    // {
    //     return json_decode($this->tasks, true) ?? [];
    // }
}
