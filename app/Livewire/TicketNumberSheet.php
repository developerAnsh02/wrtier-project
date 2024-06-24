<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\multipleswiter;


class TicketNumberSheet extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $orders = Order::with([
                'writer:id,name', 
                'subwriter:id,name', 
                'mulsubwriter', 
                'feedback.user:id,name'
            ])
            ->where('admin_id', auth()->user()->id)
            ->where('feedbackissue', '1')
            ->orderByDesc('feedback_date')
            ->orderByDesc('id')
            ->select([
                'id', 'order_id', 'services', 'typeofpaper', 
                'writer_deadline', 'wid', 'swid', 'status_issue',
                'resit', 'tech', 'feedbackissue', 'feedback_date'
            ])
            ->paginate(10);
            

        return view('livewire.ticket-number-sheet', ['orders' => $orders]);
    }
}
