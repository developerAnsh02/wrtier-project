<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskReport as TaskReportModel;
use App\Models\Order;
use App\Models\User;
use App\Models\Multipleswiter;
use Illuminate\Support\Carbon;

class WriterTaskReport extends Component
{
    public $reports = [];
    public $task_date;
    public $newReport = [
        'order_code' => '',
        'nature' => 'New',
        'word_count' => '',
        'comments' => ''
    ];

    public $searchOrderCode = '';
    public $filteredOrderCodes = [];
    public $showOrderCodeDropdown = false;
    public $editingIndex = null;
    public $confirmingDelete = null;
    
    // Edit request properties
    public $showRequestModal = false;
    public $editRequestReason = '';

    public $noResultsFound = false;    
    public $userId;    

    public function mount()
    {
        if (!in_array(Auth::user()->role_id, [6, 7])) {
            abort(403, 'Unauthorized access');
        }
        $this->userId = Auth::id();
        if (Auth::user()->role_id === 6) {
            // Try to find the writer account for this TL (must have role_id == 7)
            $writer = User::where('tl_id', $this->userId)
                    ->where('self_writer', $this->userId)
                    ->where('role_id', 7)
                    ->first();
            $this->userId = $writer->id;
        }
        $this->task_date = now()->toDateString();
        $this->loadReports();
    }

    public function loadReports()
    {
        // First try to get the latest draft copy (child report)
        $report = TaskReportModel::where('user_id', $this->userId)
            ->where('task_date', $this->task_date)
            ->where('is_hidden_from_writer', false)
            ->orderBy('version', 'desc')
            ->first();

        if ($report) {
            $this->reports = [
                'id' => $report->id,
                'user_id' => $report->user_id,
                'total_words' => $report->total_words,
                'tasks' => json_decode($report->tasks, true) ?? [],
                'task_date' => $report->task_date,
                'is_draft' => $report->is_draft,
                'submitted_at' => $report->submitted_at,
                'is_hidden_from_writer' => $report->is_hidden_from_writer,
                'version' => $report->version,
                'parent_id' => $report->parent_id,
                'edit_request_status' => $report->edit_request_status,
                'edit_reason' => $report->edit_reason,
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ];
        } else {
            $this->reports = [
                'tasks' => [],
                'total_words' => 0,
                'is_draft' => true,
                'submitted_at' => null,
                'is_hidden_from_writer' => false,
                'version' => 1,
                'parent_id' => null,
                'edit_request_status' => null,
                'edit_reason' => null,
            ];
        }
    }

    public function updatedTaskDate()
    {
        $this->resetForm();
        $this->loadReports();
    }

    public function searchOrder()
    {
        $this->resetErrorBag();
        if (strlen($this->searchOrderCode) < 2) {
            $this->filteredOrderCodes = [];
            $this->showOrderCodeDropdown = false;
            $this->noResultsFound = false;
            return;
        }
        
        // Get order IDs from Multipleswiter for current user
        $multipleWriters = Multipleswiter::where('user_id', $this->userId)->get();
        $orderIds = $multipleWriters->pluck('order_id')->toArray();

        // Search only within the user's assigned orders
        $this->filteredOrderCodes = Order::whereIn('id', $orderIds)
            ->where('order_id', 'like', '%' . $this->searchOrderCode . '%')
            ->orderBy('order_id', 'desc')
            ->limit(10)
            ->pluck('order_id')
            ->toArray();

        // $this->showOrderCodeDropdown = !empty($this->filteredOrderCodes);
        $this->noResultsFound = empty($this->filteredOrderCodes); 
        $this->showOrderCodeDropdown = true; 
    }

    public function selectOrderCode($code)
    {
        $this->newReport['order_code'] = $code;
        $this->searchOrderCode = $code;
        $this->reset(['filteredOrderCodes', 'showOrderCodeDropdown']);
    }

    public function resetForm()
    {
        $this->reset(['newReport', 'searchOrderCode', 'filteredOrderCodes', 'showOrderCodeDropdown', 'editingIndex']);
    }

    public function editTask($index)
    {
        if (isset($this->reports['is_hidden_from_writer']) && $this->reports['is_hidden_from_writer']) {
            session()->flash('error', 'Cannot edit an archived report');
            return;
        }
        
        $this->editingIndex = $index;
        $this->newReport = $this->reports['tasks'][$index];
        $this->searchOrderCode = $this->reports['tasks'][$index]['order_code'];
    }

    public function deleteTask($index)
    {
        $this->validate([
            'task_date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->toDateString() !== Carbon::today()->toDateString()) {
                    $fail('You can only delete reports for today.');
                }
            }]
        ]);
        $this->confirmingDelete = $index;
    }

    public function confirmDelete()
    {
        $report = TaskReportModel::where('user_id', $this->userId)
            ->where('task_date', $this->task_date)
            ->orderBy('version', 'desc')
            ->first();

        if ($report) {
            $existingTasks = json_decode($report->tasks, true) ?? [];
            
            if (isset($existingTasks[$this->confirmingDelete])) {
                array_splice($existingTasks, $this->confirmingDelete, 1);
                
                $report->tasks = json_encode($existingTasks);
                $report->total_words = array_sum(array_column($existingTasks, 'word_count'));
                $report->save();
                
                $this->loadReports();
            }
        }

        $this->confirmingDelete = null;
    }

    public function addReport()
    {        
        $this->validate([
            'task_date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->toDateString() !== Carbon::today()->toDateString()) {
                    $fail('You can only add/edit tasks for today\'s date');
                }
            }],
            'newReport.order_code' => 'required|string',
            'newReport.nature' => 'required|string',
            'newReport.word_count' => 'required|integer|min:0',
            'newReport.comments' => 'required|string|max:500'
        ], [
            'newReport.order_code.required' => 'Order code is required.',
            'newReport.nature.required' => 'Please select the nature of the task.',
            'newReport.word_count.required' => 'Word count is required.',
            'newReport.word_count.integer' => 'Word count must be a number.',
            'newReport.word_count.min' => 'Word count must be at least 0.',
            'newReport.comments.required' => 'Comments are required.',
            'newReport.comments.max' => 'Comments cannot exceed 500 characters.',
        ]);

        // Get order IDs assigned to current user from Multipleswiter
        $multipleWriters = Multipleswiter::where('user_id', $this->userId)->pluck('order_id')->toArray();

        // Check if the entered order_code exists in user's assigned orders
        $order = Order::whereIn('id', $multipleWriters)
            ->where('order_id', $this->newReport['order_code'])
            ->first();

        if (!$order) {
            $this->addError('newReport.order_code', 'This order code is not assigned to you.');
            return;
        }

        // Get the current report (the copy we're working with)
        $report = TaskReportModel::find($this->reports['id'] ?? null);

        // If no report exists yet (brand new report)
        if (!$report) {
            $version = 1; // Start new reports at version 1
            $parentId = null;
            
            // If we're editing an existing report (creating a copy)
            if (isset($this->reports['id'])) {
                $parentReport = TaskReportModel::find($this->reports['id']);
                $version = $parentReport->version + 1;
                $parentId = $parentReport->id;
            }

            $report = new TaskReportModel([
                'user_id' => $this->userId,
                'task_date' => $this->task_date,
                'is_draft' => true,
                'version' => $version,
                'parent_id' => $parentId
            ]);
        }

        $existingTasks = json_decode($report->tasks, true) ?? [];

        $taskData = [
            'order_code' => $this->newReport['order_code'],
            'nature' => $this->newReport['nature'],
            'word_count' => (int)$this->newReport['word_count'],
            'comments' => $this->newReport['comments'],
            'timestamp' => isset($this->reports['tasks'][$this->editingIndex]['timestamp']) 
                ? $this->reports['tasks'][$this->editingIndex]['timestamp']
                : now()->toDateTimeString(),
        ];

        if ($this->editingIndex !== null) {
            $existingTasks[$this->editingIndex] = $taskData;
        } else {
            $existingTasks[] = $taskData;
        }

        $report->tasks = json_encode($existingTasks);
        $report->total_words = array_sum(array_column($existingTasks, 'word_count'));
        $report->save();

        $this->resetForm();
        $this->loadReports();
    }

    public function finalSubmission()
    {
        $this->validate([
            'task_date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->toDateString() !== Carbon::today()->toDateString()) {
                    $fail('You can only submit reports for today');
                }
            }]
        ]);
        
        $report = TaskReportModel::where('user_id', $this->userId)
            ->where('task_date', $this->task_date)
            ->orderBy('version', 'desc')
            ->first();

        if ($report) {
            $report->update([
                'is_draft' => false,
                'submitted_at' => now(),
                'edit_request_status' => null,
                'edit_reason' => null,
            ]);

            $this->loadReports();
        }
    }
    
    public function requestEdit()
    {
        $this->validate([
            'task_date' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->toDateString() !== Carbon::today()->toDateString()) {
                    $fail('You can only request edits for today \'s report');
                }
            }]
        ]);
        $report = TaskReportModel::where('user_id', $this->userId)
            ->where('task_date', $this->task_date)
            ->orderBy('version', 'desc')
            ->first();

        if ($report && $report->edit_request_status === 'pending') {
            session()->flash('message', 'You already have a pending edit request for this report.');
            return;
        }

        $this->showRequestModal = true;
    }

    public function submitEditRequest()
    {
        $this->validate([
            'editRequestReason' => 'required|min:5|max:55'
        ]);

        $report = TaskReportModel::where('user_id', $this->userId)
            ->where('task_date', $this->task_date)
            ->orderBy('version', 'desc')
            ->first();

        if ($report) {
            $report->update([
                'edit_request_status' => 'pending',
                'edit_reason' => $this->editRequestReason,
                'updated_at' => now(),
            ]);

            $this->showRequestModal = false;
            $this->editRequestReason = '';
            $this->loadReports();
            
            session()->flash('message', 'Edit request submitted to your Team Leader.');
        }
    }

    public function cancelRequest()
    {
        $this->showRequestModal = false;
        $this->editRequestReason = '';
    }

    public function resetSearch()
    {
        $this->reset([
            'filteredOrderCodes',
            'showOrderCodeDropdown',
            'searchOrderCode',
            'noResultsFound'
        ]);
    }

    public function render()
    {
        return view('livewire.writer-task-report');
    }
}