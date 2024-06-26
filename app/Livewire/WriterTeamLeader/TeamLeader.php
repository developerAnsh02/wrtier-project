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

    public function mount()
    {
        // Fetch subwriters associated with the authenticated user
        $this->subWriters = User::where('role_id', 7)
                                ->where('flag', 0)
                                ->where('tl_id', auth()->user()->id)
                                ->get();
    }


    public function render()
    {
        $orders = Order::query();
        $data = [
            
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];
        $data['orders'] = $orders->where('wid', auth()->user()->id)->orderBy('order_id', 'desc')->paginate(10);
        return view('livewire.writer-team-leader.team-leader', compact('data'));
    }
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $this->orderId = $order->id;
        $this->status = $order->writer_status;
        $this->mulsubwriter = $order->mulsubwriter->pluck('user_id')->toArray();
        $this->isEditModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'status' => 'required|string',
            'mulsubwriter' => 'required|array',
        ]);

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
