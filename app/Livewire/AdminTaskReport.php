<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TaskReport;
use Illuminate\Support\Facades\Auth;

class AdminTaskReport extends Component
{
    public $selectedDate;
    public $tls = [];
    public $writersByTl = [];
    public $reportsByWriter = [];
    public $currentReport = null;
    public $showApprovalModal = false;
    public $showViewModal = false;

    protected $listeners = ['refreshReports' => 'loadReports'];

    public function mount()
    {
        if (!in_array(Auth::user()->role_id, [8])) {
            abort(403, 'Unauthorized access');
        }
        $this->selectedDate = now()->toDateString();
        $this->loadTLs();
        $this->loadReports();
    }

    public function loadTLs()
    {
        $this->tls = User::where('role_id', 6)
            ->where('flag', 0)
            ->where('admin_id', auth()->id())
            ->get(['id', 'name']);

        foreach ($this->tls as $tl) {
            $this->writersByTl[$tl->id] = User::where('role_id', 7)
                ->where('flag', 0)
                ->where('tl_id', $tl->id)
                ->get(['id', 'name']);
        }
    }

    public function loadReports()
    {
        $this->reportsByWriter = [];

        foreach ($this->writersByTl as $writers) {
            foreach ($writers as $writer) {
                $reports = TaskReport::with(['user:id,name'])
                    ->where('user_id', $writer->id)
                    ->whereDate('task_date', $this->selectedDate)
                    ->orderByDesc('created_at')
                    ->get()
                    ->each(function ($report) {
                        $report->append('tasks_array');
                    });

                $this->reportsByWriter[$writer->id] = $reports;
            }
        }
    }

    public function updatedSelectedDate()
    {
        $this->loadReports();
    }

    public function show($reportId)
    {
        $report = TaskReport::with('user:id,name')->findOrFail($reportId);
        $report->append('tasks_array');

        $this->currentReport = $report;
        $this->showApprovalModal = true;
    }


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
            $newVersion->edit_reason = NULL;
            $newVersion->submitted_at = NULL;
            $newVersion->save();

            $this->currentReport->update([
                'edit_request_status' => 'archived',
                'is_draft' => false,
                'is_hidden_from_writer' => true
            ]);
        });

        $this->resetModal();
        $this->dispatch('notify', type: 'success', message: 'Edit request approved!');
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
        $this->dispatch('notify', type: 'success', message: 'Edit request rejected.');
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
        return view('livewire.admin-task-report');
    }
}
