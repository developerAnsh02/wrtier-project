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

    public $modal;
    
    public function mount()
    {
        // Initialize properties with default values if needed
        // Example: $this->search = '';
    }
    public function editQc($orderId)
    {
        $orderAvailable = Order::find($orderId);
        if ($orderAvailable) {
            $this->modal = $orderAvailable->order_id;
        } else {
           return redirect()->to('/Qc-Sheets');
        }
        
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
