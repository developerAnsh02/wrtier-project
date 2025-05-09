<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskReport as TaskReportModel;
use App\Models\Order;

class TaskReport extends Component
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

    public function mount()
    {
        $this->task_date = now()->toDateString();
        $this->loadReports();
    }

    public function loadReports()
    {
        $report = TaskReportModel::where('user_id', Auth::id())
            ->where('task_date', $this->task_date)
            ->first();

        if ($report) {
            $this->reports = [
                'user_id' => $report->user_id,
                'total_words' => $report->total_words,
                'task_date' => $report->task_date,
                'is_draft' => $report->is_draft,
                'is_hidden_from_writer' => $report->is_hidden_from_writer,
                'tasks' => json_decode($report->tasks, true) ?? [],
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ];
        } else {
            $this->reports = [
                'tasks' => [],
                'total_words' => 0,
                'is_draft' => false,
                'is_hidden_from_writer' => false,
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
        if (strlen($this->searchOrderCode) < 2) {
            $this->filteredOrderCodes = [];
            $this->showOrderCodeDropdown = false;
            return;
        }

        $this->filteredOrderCodes = Order::where('wid', Auth::id())
            ->where('order_id', 'like', '%' . $this->searchOrderCode . '%')
            ->orderByDesc('id')
            ->limit(10)
            ->pluck('order_id')
            ->toArray();

        $this->showOrderCodeDropdown = !empty($this->filteredOrderCodes);
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
        $this->editingIndex = $index;
        $this->newReport = $this->reports['tasks'][$index];
        $this->searchOrderCode = $this->reports['tasks'][$index]['order_code'];
    }

    public function deleteTask($index)
    {
        $this->confirmingDelete = $index;
    }

    public function confirmDelete()
    {
        $report = TaskReportModel::where('user_id', Auth::id())
            ->where('task_date', $this->task_date)
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
            'newReport.order_code' => 'required|string',
            'newReport.nature' => 'required|string',
            'newReport.word_count' => 'required|integer|min:0',
            'newReport.comments' => 'required|string|max:500'
        ]);

        if (!Order::where('wid', Auth::id())
            ->where('order_id', $this->newReport['order_code'])
            ->exists()) {
            $this->addError('newReport.order_code', 'This order code is not assigned to you.');
            return;
        }

        $report = TaskReportModel::firstOrNew([
            'user_id' => Auth::id(),
            'task_date'=> $this->task_date,
        ]);

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
        $report = TaskReportModel::where('user_id', Auth::id())
            ->where('task_date', $this->task_date)
            ->first();

        if ($report) {
            // Update the report to mark it as final submission
            $report->update([
                'is_draft' => false,
                'submitted_at' => now(), // Optional: track submission time
            ]);

            // Reload the reports to reflect changes
            $this->loadReports();

            // Show success message
            session()->flash('message', 'Task report submitted successfully!');
        } else {
            session()->flash('error', 'No tasks found to submit.');
        }
    }
    
    public function render()
    {
        return view('livewire.task-report');
    }
}