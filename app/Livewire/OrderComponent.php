<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\multipleswiter;

class OrderComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $tl_id;
    
    public $order;
    public $status;
    public $from_date;
    public $from_date_time;
    public $upto_date;
    public $upto_date_time;
    public $selectedWriters = [];

    
    public $filterExtra;
    public $filterStatus;
    public $filterEditedOn;
    public $filterFromDate;
    public $filterToDate;
    public $search;
    // Mount method to initialize component properties
    public function mount()
    {
        $this->resetFilters();
    }
    public function resetFilters()
    {
        $this->tl_id = '';
        $this->selectedWriters = [];
        $this->filterExtra = '';
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

    // Livewire method to update the order
    public function updateOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        // dd([
        //     'orderId' => $orderId,
        //     'tl_id' => $this->tl_id,
        //     'status' => $this->status,
        //     'from_date' => $this->from_date,
        //     'upto_date' => $this->upto_date,
        //     'from_date_time' => $this->from_date_time,
        //     'upto_date_time' => $this->upto_date_time,
        //     'selectedWriters' => $this->selectedWriters,
        // ]);
        // $this->validate();
        // Update the field name
        $order->writer_status = $this->status;
        $order->wid = $this->tl_id;
        $order->writer_fd = $this->from_date;
        $order->writer_ud = $this->upto_date;
        $order->writer_fd_h = $this->from_date_time;
        $order->writer_ud_h = $this->upto_date_time;
        $order->save();
        if (!empty($this->selectedWriters)) {
            // Delete existing entries with the same order_id
            multipleswiter::where('order_id', $orderId)->delete();
            
            // Insert new entries
            foreach ($this->selectedWriters as $subwriterId) {
                $writer = new multipleswiter;
                $writer->order_id = $orderId;
                $writer->user_id = $subwriterId;
                $writer->save();
            }
        }
        
        // Clear form fields and selected writers
        $this->status = null;
        $this->from_date = null;
        $this->from_date_time = null;
        $this->upto_date = null;
        $this->upto_date_time = null;
        $this->selectedWriters = [];

        
        

        // Flash success message
        session()->flash('message', 'Order updated successfully.');

        
    }

    public function filterSubWriters()
    {
        if ($this->tl_id) {
            $data['writers'] = User::where('flag', 0)
                ->where('role_id', 7)
                ->where('tl_id', $this->tl_id)
                ->get(['id', 'name']);
        } else {
            $data['writers'] = User::where('flag', 0)->where('role_id', 7)->get(['id', 'name']);
        }

        // Clear selected writers when TL changes
        $this->selectedWriters = [];

        return $data['writers'];
    }
    public function resetTLId()
    {
        $this->tl_id = '';
    }

    public function render()
    {
        $ordersQuery = Order::with(['writer:id,name', 'subwriter:id,name','mulsubwriter' => function ($query) {$query->with('user:id,name');}])
        ->where('admin_id', auth()->user()->id)->orderBy('id', 'desc')
        ->select([
            'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
            'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
            'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
            'resit', 'tech'
        ]);

        
        if ($this->search) {
            $ordersQuery->where('order_id', 'like', '%' . $this->search . '%')
                ->orWhere('title', $this->search);
        }
        if ($this->filterStatus) {
            
            if ($this->filterStatus == 'Not Assign') {
                $ordersQuery->where(function($query) {
                    $query->whereNull('writer_status')
                            ->orWhere('writer_status', '');
                });
            } else {
                $ordersQuery->where('writer_status', $this->filterStatus);
            }
        }
        
        $data['order'] = $ordersQuery->paginate(10);
        $data['tl'] = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        // $data['writers'] = User::where('flag', 0)->where('role_id' , 7)->get(['id' , 'name' , 'admin_id']);
        $data['writers'] = $this->filterSubWriters();
        return view('livewire.order-component', compact('data'));
    }
    
}
