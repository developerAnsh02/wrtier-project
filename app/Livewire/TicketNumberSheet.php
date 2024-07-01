<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Carbon\Carbon;

class TicketNumberSheet extends Component
{
    use WithPagination;

    public $orderCode;
    public $fromDate;
    public $toDate;
    public $status;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = Order::with([
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
                'resit', 'tech', 'feedbackissue', 'feedback_date',
                'feedback_ticket'
            ]);

        if ($this->orderCode) {
            $query->where('order_id', 'like', '%' . $this->orderCode . '%');
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('feedback_date', [
                Carbon::parse($this->fromDate)->startOfDay(),
                Carbon::parse($this->toDate)->endOfDay()
            ]);
        }

        if ($this->status) {
            $query->where('status_issue', $this->status);
        }

        $orders = $query->paginate(10);

        return view('livewire.ticket-number-sheet', ['orders' => $orders]);
    }

    public function resetFilters()
    {
        $this->reset(['orderCode', 'fromDate', 'toDate', 'status']);
    }
}
