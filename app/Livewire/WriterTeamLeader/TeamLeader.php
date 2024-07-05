<?php

namespace App\Livewire\WriterTeamLeader;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\multipleswiter;

class TeamLeader extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $isEditModalOpen = false;
    public $orderId;
    public $status;
    public $mulsubwriter = [];
    public $subWriters;
    public $orderCode;
    //filter var
    public $search;
    public $filterByStatus;
    public $filterExtra;
    public $filterSubWriter;
    public $filterFromDate;
    public $filterToDate;
    public $filterFromDateApply;
    public $filterToDateApply;
    
    public function mount()
    {
        // Fetch subwriters associated with the authenticated user
        $this->subWriters = User::where('role_id', 7)
                                ->where('flag', 0)
                                ->where('tl_id', auth()->user()->id)
                                ->get();
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
        $this->filterSubWriter = ''; 
        $this->filterFromDateApply = '';
        $this->filterToDateApply = '';
        $this->filterFromDate = '';
        $this->filterToDate = '';
    }

    public function render()
    {
        $ordersQuery = Order::where('wid', auth()->user()->id)->orderBy('order_id', 'desc')
        ->select([
            'id', 'order_id', 'is_fail', 'resit', 'services', 'writer_fd', 'writer_ud', 'writer_fd_h', 'writer_ud_h', 
            'title', 'chapter', 'tech', 'writer_status', 'pages', 'wid', 'swid',
        ]);
        $data = [
            
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];

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

        if ($this->filterSubWriter) {                          
            $multipleWriters = multipleswiter::where('user_id', $this->filterSubWriter)->get();            
            $orderIds = $multipleWriters->pluck('order_id')->toArray();            
            $ordersQuery->whereIn('id', $orderIds);            
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
        
        $data['orders'] = $ordersQuery->paginate(10);
        // echo '<pre>'; print_r($data['orders']) ; exit;
        return view('livewire.writer-team-leader.team-leader', compact('data'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $this->orderId = $order->id;
        $this->status = $order->writer_status;
        $this->mulsubwriter = $order->mulsubwriter->pluck('user_id')->toArray();
        $this->orderCode = $order->order_id;
        $this->isEditModalOpen = true;
    }

    public function update()
    {
        $customMessages = [
            'mulsubwriter' => 'Please select at least one writer.',
        ];
        $this->validate([
            'status' => 'required|string',
            'mulsubwriter' => 'required|array',
        ], $customMessages);

        $order = Order::findOrFail($this->orderId);
        $order->writer_status = $this->status;

        // Detach existing relations
        $order->mulsubwriter()->delete();

        // Attach new relations
        foreach ($this->mulsubwriter as $userId) {
            $order->mulsubwriter()->create(['user_id' => $userId]);
        }

        $order->save();

        $this->isEditModalOpen = false;
    }


    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }
}
