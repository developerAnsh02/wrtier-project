<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class AvailableWriter extends Component
{
    public $filterFromDate;

    public function mount()
    {
        $this->resetFilters();
    }

    public function resetFilters()
    {
        $this->filterFromDate = '';
    }

    public function applyFilters()
    {
        // No need to reset page as Livewire will handle re-rendering
    }

    public function render()
    {
        $today = Carbon::today()->format('Y-m-d');
        $filterDate = $this->filterFromDate ?: $today;

        $users = User::with(['writerWork' => function ($query) {
                $query->select(['id', 'user_id', 'order_id']);
            }, 'writerWork.order' => function ($query) {
                $query->select(['id', 'order_id', 'writer_fd', 'writer_fd_h', 'writer_ud', 'writer_ud_h']);
            }])
            ->where('role_id', 7)
            ->whereDoesntHave('writerWork.order', function ($query) use ($filterDate) {
                $query->whereDate('writer_fd', '<=', $filterDate)
                      ->whereDate('writer_ud', '>=', $filterDate);
            })
            ->where('flag', 0)
            ->get(['id', 'name', 'email', 'mobile_no']);

        $usersWithTime = User::whereHas('writerWork.order', function ($query) use ($filterDate) {
                $query->whereDate('writer_ud', '=', $filterDate)
                      ->where('writer_ud_h', '!=', '');
            })
            ->with(['writerWork' => function ($query) use ($filterDate) {
                $query->whereHas('order', function ($query) use ($filterDate) {
                    $query->whereDate('writer_ud', '=', $filterDate)
                          ->where('writer_ud_h', '!=', '');
                });
            }])
            ->where('role_id', 7)
            ->where('flag', 0)
            ->get(['id', 'name', 'email', 'mobile_no']);

        return view('livewire.available-writer', compact('users', 'usersWithTime', 'today'));
    }
}
