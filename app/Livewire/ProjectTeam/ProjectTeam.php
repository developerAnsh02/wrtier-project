<?php

namespace App\Livewire\ProjectTeam;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;

class ProjectTeam extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $isEditModalOpen = false;

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
}
