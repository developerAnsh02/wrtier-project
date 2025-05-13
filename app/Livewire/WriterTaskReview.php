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
    public $showApprovalModal = false;
    public $showViewModal = false; 
    public $currentReport = null;
    public $writers = [];

    protected $listeners = ['refreshReports' => 'loadReports'];

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
        $this->loadWriters();
        $this->loadReports();
    }

    public function loadWriters()
    {        
        $this->writers = User::where('role_id', 7)
                            ->where('flag', 0)
                            ->where('tl_id', auth()->id())
                            ->get(['id', 'name']);
    }

    public function loadReports()
    {
        $this->reports = TaskReport::with(['user:id,name'])
            ->whereIn('user_id', $this->writers->pluck('id'))
            ->whereDate('task_date', $this->selectedDate)
            ->orderByDesc('created_at')
            ->get()
            ->each(function ($report) {
                $report->append('tasks_array');
            });
    }

    public function updatedSelectedDate()
    {
        $this->loadReports();
    }

    public function show($reportId)
    {
            $this->currentReport = TaskReport::with('user:id,name')
            ->findOrFail($reportId)
            ->append('tasks_array');
        $this->showApprovalModal = true;
    }

    // New method for view details
    public function showDetails($reportId)
    {
        $this->currentReport = TaskReport::with('user:id,name')
            ->findOrFail($reportId)
            ->append('tasks_array');
        $this->showViewModal = true;
    }

    public function approveEditRequest()
    {
        \DB::transaction(function () {
            $this->validate([
                'currentReport.edit_reason' => 'sometimes|max:255'
            ]);

            $newVersion = $this->currentReport->replicate();
            $newVersion->version = $this->currentReport->version + 1;
            $newVersion->parent_id = $this->currentReport->id;
            $newVersion->is_draft = true;
            $newVersion->edit_request_status = 'approved';
            $newVersion->edit_reason = '';
            $newVersion->submitted_at = NULL;
            $newVersion->save();

            $this->currentReport->update([
                'edit_request_status' => 'archived',
                'is_draft' => false,
                'is_hidden_from_writer' => true
            ]);
        });

        $this->resetModal();
        $this->dispatch('notify', 
            type: 'success',
            message: 'Edit request approved! Writer can now make changes.'
        );
        $this->dispatch('refreshReports');
    }

    public function rejectEditRequest()
    {
        $this->validate([
            'currentReport.id' => 'required|exists:task_reports,id'
        ]);

        $this->currentReport->update([
            'edit_request_status' => 'rejected',
            'rejected_at' => now()
        ]);

        $this->resetModal();
        $this->dispatch('notify', 
            type: 'success',
            message: 'Edit request rejected.'
        );
        $this->dispatch('refreshReports');
    }

    public function resetModal()
    {
        $this->showApprovalModal = false;
        $this->showViewModal = false;
        $this->currentReport = null;
    }

    public function render()
    {
        if (Auth::user()->role_id != 6) {
            abort(403, 'Unauthorized access');
        }
        return view('livewire.writer-task-review');
    }
}