<?php

namespace App\Livewire\ProjectTeam;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Mail;
use App\Mail\OrderComplete;
use App\Models\User;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Writer;
use App\Models\Paper;

class ProjectTeam extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // for comments
    public $commentId;
    public $isCommentModalOpen = false;
    public $selectedOrderId;
    public $comments = [];
    
    // edit modal var start
    public $isEditModalOpen = false;
    public $orderId;
    public $status;
    public $AllStatus = [];
    public $AllTeam = [];
    public $AllPaper = [];
    public $orderCode;
   
    public $module_code;
    public $project_title;
    public $order_date;
    public $writer_deadline;
    public $writer_deadline_time;
    public $delivery_date;
    public $delivery_time;
    public $chapter;
    public $type_of_paper;
    public $word;
    public $writer_name;
    public $draft_required;
    public $draft_date;
    public $draft_time;
    public $messages;
    // edit modal var end
    
    // filter var start
    public $search;
    public $filterByStatus;
    public $filterByTeam;
    public $filterExtra;
    public $filterEditedOn;
    public $filterFromDate;
    public $filterToDate;
    
    // filter var end

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterByStatus = '';
        $this->filterByTeam = '';
        $this->filterExtra = '';
        $this->filterEditedOn = '';
        $this->filterFromDate = '';
        $this->filterToDate = '';
        $this->resetPage();
    }
    public function render()
    {
        $ordersQuery = Order::with('user')->where('uid', '!=', 0)->orderBy('order_id', 'desc')
        ->select([
            'orders.id', 'orders.order_id', 'orders.is_fail', 'orders.resit', 'orders.services', 'orders.order_date',
            'orders.delivery_date', 'orders.draftrequired', 'orders.draft_date', 'orders.draft_time', 'orders.title',
            'orders.chapter', 'orders.tech', 'orders.projectstatus', 'orders.module_code',
            'orders.writer_name', 'orders.writer_deadline',
            'users.is_fail as user_is_fail' // Select the is_fail attribute from the users table
        ])
        ->join('users', 'orders.uid', '=', 'users.id');
        //filter
        if ($this->search) {            
            $ordersQuery->where(function($query) {
                $query->where('orders.order_id', 'like', '%' . $this->search . '%')
                        ->orWhere('orders.title', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterByStatus) {
            $ordersQuery->where('orders.projectstatus', $this->filterByStatus);
        }

        if ($this->filterByTeam) {
            if($this->filterByTeam == 'team 13') {
                $ordersQuery->where('orders.admin_id',  8392 );
            }else{
                $ordersQuery->where('orders.writer_name', 'Like', $this->filterByTeam );
            }
        }

        if($this->filterExtra) {
            if($this->filterExtra == 'tech') {
                $ordersQuery->where('orders.tech', '1' );
            }elseif($this->filterExtra == 'resit') {
                $ordersQuery->where('orders.resit', 'on' );
            }elseif($this->filterExtra == 'failedjob') {
                $ordersQuery->where('orders.is_fail', '1' );                
            }elseif($this->filterExtra == '1') {
                $ordersQuery->where('orders.services', 'First Class Work' );                
            }
        }

        if($this->filterEditedOn || $this->filterFromDate || $this->filterToDate) {
            if (!$this->filterEditedOn) {               
                $this->filterEditedOn = 'order_date'; // for default search by order_date if type not selected. 
            }

            if (!$this->filterFromDate && $this->filterToDate && $this->filterEditedOn) {               
                session()->flash('warning', 'Please select a from date to search with a single date.');
            }

            if ($this->filterFromDate && $this->filterToDate && $this->filterEditedOn) {
                if($this->filterEditedOn == 'draft_date') {
                    $ordersQuery->whereBetween($this->filterEditedOn, [$this->filterFromDate, $this->filterToDate])->where('draftrequired' , 'y' );
                }else{
                    $ordersQuery->whereBetween($this->filterEditedOn, [$this->filterFromDate, $this->filterToDate]);
                }                
            }elseif ($this->filterFromDate && !$this->filterToDate && $this->filterEditedOn) {
                if($this->filterEditedOn == 'draft_date') {
                    $ordersQuery->where($this->filterEditedOn, $this->filterFromDate)->where('draftrequired' , 'y' );
                }else {
                    $ordersQuery->where($this->filterEditedOn, $this->filterFromDate);
                }                
            }
        }

        $data['orders'] = $ordersQuery->paginate(10);
        $data['Status'] = Status::all();
        $data['Team'] = Writer::all();
        
        return view('livewire.project-team.project-team', compact('data'));
    }
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $this->AllStatus = Status::all();
        $this->AllTeam = Writer::all();
        $this->AllPaper = Paper::all();
        $this->orderId = $order->id;
        $this->orderCode = $order->order_id;

        $this->status = $order->projectstatus;
        $this->writer_name = $order->writer_name;
        $this->module_code = $order->module_code;
        $this->project_title = $order->title;
        $this->order_date = $order->order_date;
        $this->writer_deadline = $order->writer_deadline;
        $this->writer_deadline_time = $order->writer_deadline_time;
        $this->delivery_date = $order->delivery_date;
        $this->delivery_time = $order->delivery_time;
        $this->chapter = $order->chapter;
        $this->type_of_paper = $order->typeofpaper;
        $this->word = $order->pages;
        $this->draft_required = $order->draftrequired;
        $this->draft_date = $order->draft_date;
        $this->draft_time = $order->draft_time;
        $this->messages = $order->message;

        
        $this->isEditModalOpen = true;
    }
    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->resetValidation();
    }
    public function update()
    {
        $customMessages = [
            'project_title.required' => 'Please enter a project title.',
            'module_code.required' => 'Please enter a module code.',
            'word.numeric' => 'Please enter a numeric value.',
            'status.required' => 'Please select a status.',
        ];
        $this->validate([
            'project_title' => 'required|string',
            'module_code' => 'required|string',
            'word' => 'nullable|numeric',
            'status' => 'required|string',
        ], $customMessages);

        if (auth()->user()->role_id == 5) {
            $order = Order::findOrFail($this->orderId);

            // Fields specific to the project-team role
            $order->title = $this->project_title;
            $order->order_date = $this->order_date;
            $order->writer_deadline = $this->writer_deadline;
            $order->writer_deadline_time = $this->writer_deadline_time;
            
            // Check if the input is a numeric value            
            $order->pages = $this->word;
            
            if( $this->status == 'Completed') {
                $order->projectstatus = $this->status;
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;
                $user = User::select('name', 'email')->find($order->uid);
                $orderData = [
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'title'      => $order->title,
                    'order_code' => $order->order_id,
                    'date'       => $order->delivery_date,
                    'due'        => (int)$order->amount - (int)$order->received_amount,
                ];
                Mail::to('vikramsuthar.wm@gmail.com')->cc('vikramsuthar.wm@gmail.com')->send(new OrderComplete($orderData));
            
            }elseif( $this->status == 'Delivered') {
                // Check additional condition
                if ((int)$order->amount - (int)$order->received_amount !== 0) {
                    $this->addError('status', 'Order cannot be marked as Delivered if there is any due payment remaining.');
                    return;
                }
                               
                $order->projectstatus = $this->status;
            }else {
                $order->projectstatus = $this->status;
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;
            }
            
            $order->draftrequired = $this->draft_required;
            $order->draft_date = $this->draft_date;
            $order->draft_time = $this->draft_time;
            $order->message = $this->messages;
            $order->module_code = $this->module_code;
            $order->chapter = $this->chapter;
            
            if( $this->writer_name == 'team 13') {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '8392'; 
            }elseif($this->writer_name == 'team 02') {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '10123'; 
            }else {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '0'; 
            }
            

            if (!$this->getErrorBag()->any()) {            
                $order->save();
                $this->isEditModalOpen = false;
            }
        }        
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
