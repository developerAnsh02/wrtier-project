<?php

namespace App\Livewire\Writer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Multipleswiter;

class Writer extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // for comments
    public $commentId;
    public $isCommentModalOpen = false;
    public $selectedOrderId;
    public $comments = [];

    public $isEditModalOpen = false;
    public $orderId;
    public $status;
    public $orderCode;

    //filter var
    public $search;
    public $filterByStatus;
    public $filterExtra;
    public $filterFromDate;
    public $filterToDate;
    public $filterToDateApply;
    public $filterFromDateApply;

    public function mount()
    {
        
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->filterFromDateApply = $this->filterFromDate;
        $this->filterToDateApply = $this->filterToDate;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterByStatus = '';
        $this->filterExtra = '';
        $this->filterFromDateApply = '';
        $this->filterToDateApply = '';
        $this->filterFromDate = '';
        $this->filterToDate = '';
        $this->resetPage();
    }

    public function render()
    {
        $multipleWriters = Multipleswiter::where('user_id', auth()->user()->id)->get();            
        $orderIds = $multipleWriters->pluck('order_id')->toArray();            
        
        // Create a map: order_id => word_count
        $writerWordCounts = $multipleWriters->pluck('word_count', 'order_id');
        
        $ordersQuery = Order::whereIn('id', $orderIds)->orderBy('order_id', 'desc')
        ->select([
            'id', 'order_id', 'is_fail', 'resit', 'services', 'writer_fd', 'writer_ud', 'writer_fd_h', 'writer_ud_h', 
            'title', 'chapter', 'tech', 'writer_status', 'pages',
        ]);

        if ($this->search) {
            
            $ordersQuery->where(function($query) {
                $query->where('order_id', 'like', '%' . $this->search . '%')
                      ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterByStatus) {
            
            if ($this->filterByStatus == 'Not Assign') {
                $ordersQuery->where(function($query) {
                    $query->whereNull('writer_status')
                    ->orWhere('writer_status', 'Not Assigned')
                    ->orWhere('writer_status', '');
                });
            } else {
                $ordersQuery->where('writer_status', $this->filterByStatus);
            }
        }

        if($this->filterExtra)
        {
            if($this->filterExtra == 'tech')
            {
                $ordersQuery->where('tech', '1' );
            }
            elseif($this->filterExtra == 'resit')
            {
                $ordersQuery->where('resit', 'on' );
            }
            elseif($this->filterExtra == 'failedjob')
            {
                $ordersQuery->where('is_fail', '1' );                
            }
            elseif($this->filterExtra == '1')
            {
                $ordersQuery->where('services', 'First Class Work' );                
            }
        }
        
        if ($this->filterFromDateApply) {
            $dateRange = explode(' - ', $this->filterFromDate);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            $ordersQuery->whereBetween('writer_fd', [$startDate, $endDate]);
        }
        
        if ($this->filterToDateApply) {
            $dateRange = explode(' - ', $this->filterToDate);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            $ordersQuery->whereBetween('writer_ud', [$startDate, $endDate]);
        }

        // $data['orders'] = $ordersQuery->paginate(10);
        $orders = $ordersQuery->paginate(10);

        $orders->getCollection()->transform(function ($order) use ($writerWordCounts) {
            $order->word_count = $writerWordCounts[$order->id] ?? null;
            return $order;
        });

        $data['orders'] = $orders;

        return view('livewire.writer.writer', compact('data'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $this->orderId = $order->id;
        $this->status = $order->writer_status;
        $this->orderCode = $order->order_id;
        $this->isEditModalOpen = true;
    }

    public function update()
    {
        
        $this->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($this->orderId);
        if ($this->status != '' && $this->status != 'Not Assigned') {
            $order->writer_status = $this->status;    
            $order->save();
        }

        $this->isEditModalOpen = false;
    }


    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }

    public function viewComments($orderId)
    {
        $this->orderId = $orderId;
        $this->selectedOrderId = Order::find($orderId)->order_id;
        $this->comments = Comment::where('order_id', $orderId)->where('is_deleted', false)->orderByDesc('created_at')->get();
        $this->isCommentModalOpen = true;
    }
    public function closeCommentModal()
    {
        $this->isCommentModalOpen = false;
        $this->comment = '';
        $this->commentId = null;
    }
}
