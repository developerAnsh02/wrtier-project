<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaskReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WriterTaskReview extends Component
{
    public $reports = [];
    public $selectedDate;
    public $writers = [];

    public function mount()
    {
        abort_unless(Auth::user()->role_id === 6, 403, 'Unauthorized access');

        $this->selectedDate = now()->toDateString();
        $this->loadWriters();
        $this->loadReports();
    }

    public function updatedSelectedDate()
    {
        $this->loadReports();
    }

    private function loadWriters()
    {
        $this->writers = User::where([
            ['role_id', 7],
            ['flag', 0],
            ['tl_id', Auth::id()],
        ])->get(['id', 'name']);
    }

    private function loadReports()
    {
        $this->reports = TaskReport::with('user:id,name')
            ->whereIn('user_id', $this->writers->pluck('id'))
            ->whereDate('task_date', $this->selectedDate)
            ->where('is_hidden_from_writer', false)
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.writer-task-review');
    }
}
