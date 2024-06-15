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
    
    protected $rules = [
        'qc_status' => 'required',
        'qc_standard' => 'required',
        'comment' => 'required',
    ];

    public function editQc($orderId)
    {
        $orderAvailable = Order::find($orderId);
        if ($orderAvailable) {
            $this->order = $orderAvailable;
            //------------------
            $this->qc_status = $orderAvailable->writer_status;
            $this->qc_standard = $orderAvailable->qc_standard;
            $this->comment = $orderAvailable->qc_comment;
        } else {
           return redirect()->to('/Qc-Sheets');
        }
        
    }
    public function updateQc($id)
    {
        $this->validate();

        $orderUpdate = Order::find($id);
        // dd($orderUpdate);
        $orderUpdate->writer_status = $this->qc_status;
        $orderUpdate->qc_standard = $this->qc_standard;
        $orderUpdate->qc_comment = $this->comment;
        
        // Debugging output
        // dd($id, $this->qc_status, $this->qc_standard, $this->comment);

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


   

    public function render()
    {
        $data = [];

        $data['executives'] = User::where('role_id', 3)->get();
        $data['writers'] = User::where('role_id', 6)->where('flag', 0)->get();
        $data['subWriters'] = User::where('role_id', 7)->where('flag', 0)->get();

        $ordersQuery = Order::with('writer', 'mulsubwriter', 'subwriter')
            ->whereNotNull('admin_id')
            ->where('admin_id', '!=', 0)
            ->orderBy('created_at', 'desc');

        

        $data['orders'] = $ordersQuery->paginate(10);

        return view('livewire.qc-sheet', $data);
    }
}
