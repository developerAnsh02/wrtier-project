<?php

namespace App\Livewire\ProjectTeam;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Status;
use App\Models\Writer;
use App\Models\Paper;

class ProjectTeam extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // edit modal var
    public $isEditModalOpen = false;
    public $orderId;
    public $status;
    public $AllStatus = [];
    public $AllTeam = [];
    public $AllPaper = [];
    public $orderCode;
    //
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
        $data['orders'] = $ordersQuery->paginate(10);
        // echo '<pre>'; print_r($data['orders']) ; exit;
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
    }
    public function update()
    {
        $customMessages = [
            'project_title' => 'Please select at least one project_title.',
        ];
        $this->validate([
            'project_title' => 'required|string',
        ], $customMessages);

        $order = Order::findOrFail($this->orderId);
        
        
        // $order->save();
        if (auth()->user()->role_id == 5) {
            // Fields specific to the project-team role
            $order->title = $this->project_title;
            $order->order_date = $this->order_date;
            $order->writer_deadline = $this->writer_deadline;
            $order->writer_deadline_time = $this->writer_deadline_time;
            // Check if the input is a numeric value
            if ($this->word && !is_numeric($this->word)) {
                dd('Word must be a numeric value');
                // return redirect()->back()->with('warning', 'Word must be a numeric value');
            }
            $order->pages = $this->word;
            
            if( $this->status == 'Completed')
            {
                $order->projectstatus = $this->status;
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;

                // $orderData = [
                //     'name' => $req->input('user_name'),
                //     'email' => $req->input('email'),
                //     'title' => $req->input('title'),
                //     'order_code' => $order->order_id,
                //     'date'     => $order->delivery_date,
                //     'due'     => $req->input('amount') - $req->input('r_amount'),
                // ];
                // Mail::to('vikramsuthar.wm@gmail.com')->cc('vikramsuthar.wm@gmail.com')->send(new OrderComplete($orderData));
               dd($order->projectstatus, $order->status_date, $order->status_by);
            }
            elseif( $this->status == 'Delivered')
            {
                if ((int)$order->amount - (int)$order->received_amount !== 0) {
                    dd("Order cannot be marked as Delivered if there is any due payment remaining.");
                    // return redirect()->back()->with('warning' , 'Order cannot be marked as Delivered if there is any due payment remaining.');
                }                
                $order->projectstatus = $this->status;
            }
            else
            {
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
            
            if( $this->writer_name == 'team 13')
            {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '8392'; 
            } 
             elseif($this->writer_name == 'team 02')
            {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '10123'; 
            }
            else
            {
                $order->writer_name = $this->writer_name;
                $order->admin_id =  '0'; 
            }
            // dd($order->writer_name, $order->admin_id);

        }
        $this->isEditModalOpen = false;

    }
}
