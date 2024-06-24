<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\multipleswiter;

class QcSheet extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $order;    
    public $qc_status;
    public $qc_standard;
    public $comment;
    public $ai_score_input;

    // Filter properties
    public $filterWriter;
    public $filterSubWriter;
    public $filterQcStandard;
    public $filterStatus;
    public $filterEditedOn;
    public $filterFromDate;
    public $filterToDate;
    public $search;
    
    protected $rules = [
        'qc_status' => 'required',
        'qc_standard' => 'required',
        'comment' => 'required',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    // Fetch sub-writers based on selected writer or fetch all if none selected
    public function filterSubWriters()
    {
        if ($this->filterWriter) {
            $subWriters = User::where('role_id', 7)
                ->where('flag', 0)
                ->where('tl_id', $this->filterWriter) 
                ->get();
        } else {
            $subWriters = User::where('role_id', 7)
                ->where('flag', 0)
                ->get();
        }
        return $subWriters;
    }

    public function resetFilters()
    {
        $this->filterWriter = '';
        $this->filterSubWriter = '';
        $this->filterQcStandard = '';
        $this->filterStatus = '';
        $this->filterEditedOn = '';
        $this->filterFromDate = '';
        $this->filterToDate = '';
        $this->search = '';
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function editQc($orderId)
    {
        $orderAvailable = Order::find($orderId);
        if ($orderAvailable) {
            $this->order = $orderAvailable;
            
            $this->qc_status = $orderAvailable->writer_status;
            $this->qc_standard = $orderAvailable->qc_standard;
            $this->comment = $orderAvailable->qc_comment;

            $this->ai_score_input = $orderAvailable->ai_score;
        } else {
           return redirect()->to('/Qc-Sheets');
        }        
    }

    public function updateQc($id)
    {
        $this->validate();

        $orderUpdate = Order::find($id);
        
        $orderUpdate->writer_status = $this->qc_status;
        $orderUpdate->qc_standard = $this->qc_standard;
        $orderUpdate->qc_comment = $this->comment;
               
        $orderUpdate->save();

        $this->resetFields();

        session()->flash('message', 'Order updated successfully.');
        
    }

    public function resetFields()
    {
        $this->qc_status = '';
        $this->qc_standard = '';
        $this->comment = '';
    }

    public function toggleCheck($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->qc_checked = !$order->qc_checked;
        $order->qc_admin = auth()->user()->name;
        $order->save();
        
        session()->flash('message', 'Checkbox updated successfully.');
    }

    public function updateAiScore($id)
    {
        $validatedData = $this->validate([
            'ai_score_input' => 'nullable|numeric|min:0|max:100',
        ]);

        $orderUpdate = Order::find($id);
        if (!$orderUpdate) {
            return redirect()->to('/Qc-Sheets');
        }

        $orderUpdate->ai_score = $this->ai_score_input;

        $orderUpdate->save();

        $this->reset(['ai_score_input']);

        session()->flash('message', 'AI Score updated successfully.');
    }

    public function render()
    {
        $executives = User::where('role_id', 3)->get();
        $writers = User::where('role_id', 6)->where('flag', 0)->get();
        $subWriters = $this->filterSubWriters();

        $ordersQuery = Order::with([
            'writer:id,name',
            'subwriter:id,name',
            'mulsubwriter' => function ($query) {
                $query->with('user:id,name');
            }
        ])
        ->whereNotNull('admin_id')
        ->where('admin_id', '!=', 0)
        ->orderBy('created_at', 'desc')
        ->select([
            'id', 'order_id', 'qc_admin', 'wid', 'writer_deadline', 'qc_date',
            'writer_status', 'qc_standard', 'ai_score', 'qc_comment', 'qc_checked'
        ]);

        // Apply filters
        if ($this->filterWriter) {
            $ordersQuery->where('wid', $this->filterWriter);
        }

        if ($this->filterSubWriter) {                          
            $multipleWriters = multipleswiter::where('user_id', $this->filterSubWriter)->get();            
            $orderIds = $multipleWriters->pluck('order_id')->toArray();            
            $ordersQuery->whereIn('id', $orderIds);            
        }

        if ($this->filterQcStandard) {
            $ordersQuery->where('qc_standard', $this->filterQcStandard);
        }

        if ($this->filterStatus) {
            $ordersQuery->where('writer_status', $this->filterStatus);
        }

        if ($this->filterFromDate != '' && $this->filterToDate != '') {
            if ($this->filterEditedOn != 'Qc-date') {
                $ordersQuery->whereBetween('writer_deadline', [$this->filterFromDate, $this->filterToDate]);
            } elseif ($this->filterEditedOn == 'Qc-date') {
                $ordersQuery->whereBetween('qc_date', [$this->filterFromDate, $this->filterToDate]);
            }
        } elseif ($this->filterFromDate != '') {
            if ($this->filterEditedOn != 'Qc-date') {
                $ordersQuery->whereDate('writer_deadline', $this->filterFromDate);
            } elseif ($this->filterEditedOn == 'Qc-date') {
                $ordersQuery->whereDate('qc_date', $this->filterFromDate);
            }
        }

        if ($this->search) {
            $ordersQuery->where('order_id', 'like', '%' . $this->search . '%');
        }

        $orders = $ordersQuery->paginate(10);

        return view('livewire.qc-sheet', compact('executives', 'writers', 'subWriters', 'orders'));
    }
}
