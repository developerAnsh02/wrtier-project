<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\Multipleswiter;
use App\Models\Comment;
use App\Models\Paper;
use Illuminate\Support\Facades\Auth;

class OrderComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    // for comments
    public $comment;
    public $commentId;
    public $isCommentModalOpen = false;
    public $selectedOrderId;
    public $comments = [];
    // modal var
    public $isEditModalOpen = false;
    public $order; //for edit modal
    public $orderCode;
    public $orderId;
    public $tl_id_edit;    
    public $status;
    public $modalTL = [];
    public $modalWriter = [];
    public $selectedWriters = [];
    public $from_date;
    public $from_date_time;
    public $upto_date;
    public $upto_date_time;

    public $paperTypes = [];
    public $type_of_paper;
    
    // filter
    public $search;
    public $tl_id;
    public $filter_tl_id;
    public $filterSubWriter;
    public $filterExtra;
    public $filterStatus;
    public $filterEditedOn;
    public $filterFromDate;
    public $filterToDate;
    public $newWriter = [];
    public $filterFromDateRange;
    public $filterToDateRange;
    public $filterFromDateRangeApply;
    public $filterToDateRangeApply;
    public $filterPaperType;
    
    // for word count of each writer
    public $writerWordCounts = []; // key: user_id, value: word_count
    protected $rules = [];

    public function mount()
    {
        $this->resetFilters();
        $this->filterSubWriters();
        $this->refreshOrders();
    }
    
    public function filterSubWriters()
    {
        if ($this->tl_id) {
            $this->newWriter  = User::where('flag', 0)
                ->where('role_id', 7)
                ->where('tl_id', $this->tl_id)
                ->get(['id', 'name']);
        } else {
            $this->newWriter  = User::where('flag', 0)->where('role_id', 7)->get(['id', 'name']);
        }

        $this->filterSubWriter = '';
    }
    
    public function resetFilters()
    {
        $this->tl_id = '';
        $this->filter_tl_id = '';
        $this->filterSubWriter = '';
        $this->filterSubWriters();
        $this->filterExtra = '';
        $this->filterStatus = '';
        $this->filterEditedOn = '';
        $this->filterFromDate = '';
        $this->filterToDate = '';
        $this->search = '';
        $this->filterFromDateRangeApply = '';
        $this->filterToDateRangeApply = '';
        $this->filterFromDateRange = '';
        $this->filterToDateRange = '';
        $this->filterPaperType = '';
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->filter_tl_id = $this->tl_id;
        $this->filterFromDateRangeApply = $this->filterFromDateRange;
        $this->filterToDateRangeApply = $this->filterToDateRange;
    }    

    public function render()
    {
        $ordersQuery = Order::with(['writer:id,name', 'subwriter:id,name','mulsubwriter' => function ($query) {$query->with('user:id,name');},'comments.user'])
        ->where('admin_id', auth()->user()->id)->orderBy('id', 'desc')
        ->select([
            'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
            'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
            'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
            'resit', 'tech', 'is_fail', 'draftrequired', 'draft_date', 'draft_time', 'typeofpaper', 'writer_deadline_time'
        ]);

        
        if ($this->search) {
            
            $ordersQuery->where(function($query) {
                $query->where('order_id', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->filterStatus) {
            
            if ($this->filterStatus == 'Not Assigned') {
                $ordersQuery->where(function($query) {
                    $query->whereNull('writer_status')
                        ->orWhere('writer_status', 'Not Assigned')
                            ->orWhere('writer_status', '');
                });
            } else {
                $ordersQuery->where('writer_status', $this->filterStatus);
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

        if ($this->filterEditedOn || $this->filterFromDate || $this->filterToDate) {
            if ($this->filterFromDate && $this->filterToDate && $this->filterEditedOn) 
            {
                if($this->filterEditedOn == 'draft_date')
                {
                    $ordersQuery->whereBetween($this->filterEditedOn, [$this->filterFromDate, $this->filterToDate])->where('draftrequired' , 'y' );
                }else
                {
                    $ordersQuery->whereBetween($this->filterEditedOn, [$this->filterFromDate, $this->filterToDate]);
                }                
            }elseif ($this->filterFromDate == '' || $this->filterToDate == '' && $this->filterEditedOn == 'draft_date') 
            {               
                session()->flash('warning', 'Please select both date for Draft Date');
            }elseif($this->filterFromDate != '' && $this->filterToDate != '')
            {
                $ordersQuery->whereBetween('writer_deadline', [$this->filterFromDate, $this->filterToDate]);
            }elseif($this->filterFromDate != '')
            {
                $ordersQuery->where('writer_deadline', $this->filterFromDate);
            }
        }
        
        if ($this->filter_tl_id) {
            $ordersQuery->where('wid', $this->filter_tl_id);
        }

        if ($this->filterSubWriter) {                          
            $multipleWriters = Multipleswiter::where('user_id', $this->filterSubWriter)->get();            
            $orderIds = $multipleWriters->pluck('order_id')->toArray();            
            $ordersQuery->whereIn('id', $orderIds);            
        }
        
        if ($this->filterFromDateRangeApply) {
            $dateRange = explode(' - ', $this->filterFromDateRange);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            $ordersQuery->whereBetween('writer_fd', [$startDate, $endDate]);
        }
        
        if ($this->filterToDateRangeApply) {
            $dateRange = explode(' - ', $this->filterToDateRange);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            $ordersQuery->whereBetween('writer_ud', [$startDate, $endDate]);
        }

        if ($this->filterPaperType) {
            $ordersQuery->where('typeofpaper', $this->filterPaperType);
        }

        $WordCount = $ordersQuery->get();

        $data['order'] = $ordersQuery->paginate(10);

        // Calculate the total word count for the pages
        $totalWordCount = $WordCount->reduce(function ($carry, $order) {
            return $carry + (is_numeric($order->pages) ? $order->pages : 0);
        }, 0);

        // Count the total number of orders
        $totalOrders = $WordCount->count();
        $data['totalWordCount'] = $totalWordCount;
        $data['totalOrders'] = $totalOrders;
        // dd($totalWordCount, $totalOrders);
        $data['tl'] = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        $data['paperTypes'] = Paper::orderBy('paper_type')->get();

        return view('livewire.order-component', compact('data'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $this->orderId = $order->id;
        $this->tl_id_edit = $order->wid;

        $this->modalTL = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        $this->status = $order->writer_status;
        $this->SubWritersEdit();

        // Fetch the collection of multiples writers for edit
        $multipleswitersForEdit = Multipleswiter::where('order_id', $this->orderId)->get();
        $this->selectedWriters = $multipleswitersForEdit->pluck('user_id')->toArray();
        
        // Set word counts for each selected writer
        foreach ($multipleswitersForEdit as $writerAssignment) {
            $this->writerWordCounts[$writerAssignment->user_id] = $writerAssignment->word_count ?? 0;
        }
        
        $this->orderCode = $order->order_id;

        $this->from_date = $order->writer_fd;
        $this->upto_date = $order->writer_ud;
        $this->from_date_time = $order->writer_fd_h;
        $this->upto_date_time = $order->writer_ud_h;

        //typeofpaper
        $this->type_of_paper = $order->typeofpaper;
        $this->paperTypes = Paper::orderBy('paper_type')->get();

        $this->isEditModalOpen = true;
    }
    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->orderId = '';
        $this->tl_id_edit = '';
        $this->from_date = '';
        $this->upto_date = '';
        $this->from_date_time = '';
        $this->upto_date_time = '';
        $this->status = '';
        $this->from_date = '';
        $this->from_date_time = '';
        $this->upto_date = '';
        $this->upto_date_time = '';
        $this->selectedWriters = [];
        $this->type_of_paper = '';
        $this->paperTypes = [];
        $this->resetErrorBag();
    }
    
    // Livewire method to update the order
    public function updateOrder()
    {
        // Build dynamic rules for word counts
        $wordCountRules = [];
        $wordCountMessages = [];

        foreach ($this->selectedWriters as $userId) {
            $wordCountRules["writerWordCounts.$userId"] = 'required|numeric|min:0';
            $wordCountMessages["writerWordCounts.$userId.required"] = "Word count is required for selected writer.";
            $wordCountMessages["writerWordCounts.$userId.min"] = "Word count must be at least 0 for selected writer.";
        }

        $this->validate($wordCountRules, $wordCountMessages);
        $order = Order::findOrFail($this->orderId);

        // Check total assigned writer word count does not exceed order total
        $totalWriterWords = collect($this->selectedWriters)
            ->sum(fn($userId) => (int) ($this->writerWordCounts[$userId] ?? 0));

        $maxAllowedWords = (int) $order->pages; // total word count allowed

        if ($totalWriterWords > $maxAllowedWords) {
            $this->addError('writerWordCounts', "Total assigned words ($totalWriterWords) exceed the allowed word count of $maxAllowedWords.");
            return;
        }

        // dd([
        //     'order' => $order,
        //     'orderId' => $this->orderId,
        //     'tl_id' => $this->tl_id_edit,
        //     'status' => $this->status,
        //     'from_date' => $this->from_date,
        //     'upto_date' => $this->upto_date,
        //     'from_date_time' => $this->from_date_time,
        //     'upto_date_time' => $this->upto_date_time,
        //     'selectedWriters' => $this->selectedWriters,
        // ]);
        $customMessages = [
            'tl_id_edit.required' => 'Select a TL first.',
            'selectedWriters.required' => 'Please select at least one writer.',
        ];

        $this->validate([
            'tl_id_edit' => 'required',
            'selectedWriters' => 'array',
        ], $customMessages);
        // Update the field name
        // $order->writer_status = $this->status;
        // $order->wid = $this->tl_id_edit;
        // $order->writer_fd = $this->from_date;
        // $order->writer_ud = $this->upto_date;
        // $order->writer_fd_h = $this->from_date_time;
        // $order->writer_ud_h = $this->upto_date_time;
        // $order->save();
        $updateNeeded = false;

        if ($this->status != '') {
            $order->writer_status = $this->status;
            $updateNeeded = true;
        }

        if ($this->tl_id_edit != '') {
            $order->wid = $this->tl_id_edit;
            if (empty($this->selectedWriters)) {
                // Delete existing entries with the same order_id
                Multipleswiter::where('order_id', $this->orderId)->delete();
            }
            $updateNeeded = true;
        }

        if ($this->from_date != '') {
            $order->writer_fd = $this->from_date;
            $updateNeeded = true;
        }

        if ($this->upto_date != '') {
            $order->writer_ud = $this->upto_date;
            $updateNeeded = true;
        }

        if ($this->from_date_time != '') {
            $order->writer_fd_h = $this->from_date_time;
            $updateNeeded = true;
        }

        if ($this->upto_date_time != '') {
            $order->writer_ud_h = $this->upto_date_time;
            $updateNeeded = true;
        }
        if ($this->type_of_paper != '') {
            $order->typeofpaper = $this->type_of_paper;
            $updateNeeded = true;
        }

        if ($updateNeeded) {
            $order->save();
            $updateNeeded = false;
        }

        if (!empty($this->selectedWriters)) {
            // Delete existing entries with the same order_id
            Multipleswiter::where('order_id', $this->orderId)->delete();
            
            // Insert new entries
            foreach ($this->selectedWriters as $subwriterId) {
                $writer = new Multipleswiter;
                $writer->order_id = $this->orderId;
                $writer->user_id = $subwriterId;
                $writer->word_count = $this->writerWordCounts[$subwriterId] ?? 0;
                $writer->save();
            }
        }
        
        // Clear form fields and selected writers
        // $this->status = null;
        // $this->from_date = null;
        // $this->from_date_time = null;
        // $this->upto_date = null;
        // $this->upto_date_time = null;
        // $this->selectedWriters = [];

        // $this->resetPage();
        
        $this->closeEditModal();
        // Flash success message
        session()->flash('message', 'Order updated successfully.');
        $this->errorMessage = null;
        
    }

    
    public function SubWritersEdit()
    {
        if ($this->tl_id_edit) {
            $this->modalWriter = User::where('flag', 0)
                ->where('role_id', 7)
                ->where('tl_id', $this->tl_id_edit)
                ->get(['id', 'name']);
        } else {
            $this->modalWriter = [];
        }
        // Clear selected writers when TL changes
        $this->selectedWriters = [];        
    }
    public function updated($field)
    {
        $this->validateOnly($field, [
            'tl_id_edit' => 'required|string',
            'selectedWriters' => 'required|array',
        ]);
    }

    public function refreshOrders()
    {
        $this->orders = Order::with('comments.user')->paginate(10);
    }

    public function viewComments($orderId)
    {
        $this->orderId = $orderId;
        $this->selectedOrderId = Order::find($orderId)->order_id;
        $this->comments = Comment::where('order_id', $orderId)->where('is_deleted', false)->orderByDesc('created_at')->get();
        $this->isCommentModalOpen = true;
    }

    public function addComment()
    {
        if (Auth::user()->role_id != 8) {
            session()->flash('error', 'You are not allowed to comment.');
            return;
        }

        $this->validate(['comment' => 'required|min:3']);

        Comment::create([
            'order_id' => $this->orderId,
            'user_id' => Auth::id(),
            'comment' => $this->comment,
        ]);

        $this->comment = '';
        $this->viewComments($this->orderId);
        session()->flash('message', 'Comment added successfully!');
    }

    public function editComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Auth::user()->id != $comment->user_id) {
            session()->flash('error', 'You can only edit your own comments.');
            return;
        }

        $this->commentId = $comment->id;
        $this->comment = $comment->comment;
    }

    public function updateComment()
    {
        if (!$this->commentId) return;

        $comment = Comment::findOrFail($this->commentId);

        if (Auth::user()->id != $comment->user_id) {
            session()->flash('error', 'You can only edit your own comments.');
            return;
        }

        $this->validate(['comment' => 'required|min:3']);

        $comment->update(['comment' => $this->comment]);

        $this->commentId = null;
        $this->comment = '';
        $this->viewComments($this->orderId);
        session()->flash('message', 'Comment updated successfully!');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Auth::user()->id != $comment->user_id) {
            session()->flash('error', 'You can only delete your own comments.');
            return;
        }

        // Soft delete by setting `is_deleted` to true
        $comment->update(['is_deleted' => true]);

        $this->viewComments($this->orderId);
        session()->flash('message', 'Comment deleted successfully!');
    }


    public function closeCommentModal()
    {
        $this->isCommentModalOpen = false;
        $this->comment = '';
        $this->commentId = null;
    }
    
}
