<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Order</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Order</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SR NO</th>
                                    <th>Order ID</th>
                                    <th>Delivery From - Upto</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Words</th>
                                    <th>Writer&TL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['order'] as $index => $order)
                                <tr>
                                    <td>{{ ($data['order'] ->currentPage() - 1) * $data['order'] ->perPage() + $loop->index + 1 }} </td>
                                    <td>
                                        {{$order->order_id}}
                                    </td>
                                    <td>
                                        @if ($order->writer_fd && $order->writer_fd != '0000-00-00')
                                            <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_fd)->format('jS M ') }} <div style="color:red;  margin-left: 10px;"> {{ $order->writer_fd_h }}</div> </div> <br>
                                            <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_ud)->format('jS M ') }} <div style="color:red;  margin-left: 10px;">{{ $order->writer_ud_h }}</div></div>
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($order->writer_deadline)->format('jS M Y') }}
                                    </td>
                                    <td>
                                        @if($order->writer_status == '' || $order->writer_status == 'Not Assigned' || $order->writer_status == 'Hold')
                                            <div class="label label-table label-danger">@if($order->writer_status == 'Hold') {{$order->writer_status}} @else Not Assigned @endif </div>
                                        @elseif($order->writer_status == 'In Progress' || $order->writer_status == 'In progress')
                                            <div class="label label-table label-info">{{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Ready' || $order->writer_status == 'Attached to Email (Complete file)' || $order->writer_status == 'Complete file Ready' || $order->writer_status == "Attached to Email (Draft)")
                                            <div class="label label-table label-warning">{{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Feedback' || $order->writer_status == 'Feedback')
                                            <div class="label label-table label-warning" style="color: white; background: black;">{{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Delivered' ||  $order->writer_status == 'Feedback Delivered' ||$order->writer_status == 'Delivered'  )
                                            <div class="label label-table label-success" >{{ $order->writer_status }}</div>
                                        @else
                                            {{ $order->writer_status }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->pages }}
                                    </td>
                                    <td>
                                        @if($order->wid)
                                            @if($order['writer'] &&  $order['writer']['name'])
                                                {{ $order['writer']['name'] }} <br>
                                            @endif
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @endif

                                        @if($order->mulsubwriter)
                                            @foreach($order->mulsubwriter as $writer)
                                                @if($writer->user &&  $writer->user->name) 
                                                <div class="label label-table label-info">{{$writer->user->name}}</div> <br>
                                                @endif
                                            @endforeach

                                        @elseif($order->swid)
                                                <div class="label label-table label-info">
                                                    @if(order->subwriter && $order->subwriter->name)
                                                        {{$order->subwriter->name}}
                                                    @endif
                                                </div>
                                        @endif
                                    </td>
                                
                                    <td class="text-nowrap" >
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal{{$order->order_id}}" wire:click="" class="btn btn-sm btn-light-primary"><li class="fa fa-edit"> </li></button>
                                    </td>
                                </tr>
                                    <!-- Edit Order Modal Start -->
<div wire:key="modal{{ $order->order_id }}" wire:ignore.self class="modal fade" id="editModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="updateOrder({{ $order->id }})">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-9 text-center">
                        <div class="col pb-2">
                            <div class="btn w-100 btn-outline-secondary p-2">{{ $order->order_id }}</div>
                        </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <select wire:model="tl_id" wire:change="filterSubWriters" class="form-control form-select mt-3" id="writer-tl{{ $order->id }}">
                                    <option value="">Select TL</option>
                                    @foreach($data['tl'] as $tl)
                                    <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row d-flex align-items-center">
                        <div class="col-md-12">
                            <div class="form-group has-success writer{{$order->id}}">
                                <select wire:model="selectedWriters" name="writers[]" id="writer{{$order->id}}" multiple>
                                    @foreach($data['writers'] as $writer)
                                        @php
                                            $user_ids = $order->multiple->user_id ?? [];
                                        @endphp
                                        @if($writer->admin_id == $order->wid)
                                            <option value="{{ $writer->id }}" {{ in_array($writer->id, $user_ids) ? 'selected' : '' }}>
                                                {{ $writer->name }}
                                            </option>
                                        @else
                                            <option value="{{ $writer->id }}">
                                                {{ $writer->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
                    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>


                    <script>
                        new MultiSelectTag('writer{{$order->id}}')  
                    </script>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <select wire:model="status" class="form-control form-select mt-3" id="status{{ $order->id }}">
                                    <option value="">Not Assigned</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Inprogress">Inprogress</option>
                                    <option value="dfd">jdf</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input wire:model="from_date" type="date" class="form-control" id="from_date{{ $order->id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input wire:model="from_date_time" type="time" class="form-control" id="from_date_time{{ $order->id }}">
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input wire:model="upto_date" type="date" class="form-control" id="upto_date{{ $order->id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input wire:model="upto_date_time" type="time" class="form-control" id="upto_date_time{{ $order->id }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // JavaScript to reset fields and dropdowns on modal close
    document.addEventListener('DOMContentLoaded', function () {
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const modalId = modal.id;
                const modalContent = modal.querySelector('.modal-content');

                // Reset select elements
                modalContent.querySelectorAll('select').forEach(select => {
                    select.value = '';
                });

                // Reset input elements
                modalContent.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });

                // Reset checkboxes
                modalContent.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Trigger Livewire method to reset TL select and writers selection
                @this.call('resetTLId');
                @this.set('selectedWriters', []);

                // Reset dropdown buttons text
                modalContent.querySelectorAll('.dropdown-toggle').forEach(btn => {
                    btn.innerText = 'Select';
                });
            });
        });
    });
</script>
<!-- Edit Order Modal End -->

                                @endforeach
                            </tbody>
                        </table>
                        {{ $data['order']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
