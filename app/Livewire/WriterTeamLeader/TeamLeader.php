<?php

namespace App\Livewire\WriterTeamLeader;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\Comment;
use App\Models\multipleswiter;

class TeamLeader extends Component
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
    
    // for word count of each writer
    public $writerWordCounts = []; // key: user_id, value: word_count

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
        $this->resetPage();
    }

    public function render()
    {
        $ordersQuery = Order::with([
            'writer:id,name', 
            'subwriter:id,name', 
            'mulsubwriter' => function ($query) {
                $query->with('user:id,name'); 
            }
        ])->where('wid', auth()->user()->id)->orderBy('order_id', 'desc')
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

        $this->writerWordCounts = [];

        foreach ($order->mulsubwriter as $writer) {
            $this->writerWordCounts[$writer->user_id] = $writer->word_count;
        }
        
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

        // Build dynamic rules for selected writer word counts
        $wordCountRules = [];
        $wordCountMessages = [];

        foreach ($this->mulsubwriter as $userId) {
            $wordCountRules["writerWordCounts.$userId"] = 'required|numeric|min:1';
            $wordCountMessages["writerWordCounts.$userId.required"] = "Word count is required for selected writer.";
            $wordCountMessages["writerWordCounts.$userId.min"] = "Word count must be at least 1 for selected writer.";
        }

        // Validate word counts dynamically
        $this->validate($wordCountRules, $wordCountMessages);
        
        $order = Order::findOrFail($this->orderId);

        // Calculate total assigned writer word count
        $totalWriterWords = collect($this->mulsubwriter)
            ->sum(fn($userId) => (int) ($this->writerWordCounts[$userId] ?? 0));

        // Validate against total words from `pages` field
        $maxAllowedWords = (int) $order->pages; // pages field is total word count

        if ($totalWriterWords > $maxAllowedWords) {
            $this->addError('writerWordCounts', "Total assigned words ($totalWriterWords) exceed the allowed word count of $maxAllowedWords.");
            return;
        }
        
        $order->writer_status = $this->status;

        // Detach existing relations
        $order->mulsubwriter()->delete();

        // Attach new relations
        foreach ($this->mulsubwriter as $userId) {
            $order->mulsubwriter()->create(['user_id' => $userId, 'word_count' => $this->writerWordCounts[$userId] ?? 0,]);
        }

        $order->save();

        $this->isEditModalOpen = false;
    }


    public function closeEditModal()
    {
        $this->resetErrorBag();
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
