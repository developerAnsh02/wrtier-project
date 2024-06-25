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
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible" id="alert">
                {{ session()->get('message') }}
            </div>
        @endif
        @if(session()->has('warning'))
            <div class="alert alert-warning alert-dismissible" id="alert">
                {{ session()->get('warning') }}
            </div>
        @endif
        <style>
            #alert {
            animation: fadeOut 2s forwards;
            animation-delay: 1s;
            }
            @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
            }
        </style>
        <script>
            setTimeout(function() {
                document.getElementById("alert").style.display = "none";
            }, 5000); // 5000ms = 5 seconds
        </script>
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fs-5 mb-1">Filter</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form wire:submit.prevent="applyFilters">
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <input wire:model.debounce.300ms="search" type="search" name="search" id="search" class="form-control form-control-solid form-select-lg" placeholder="OrderCode, Title">
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="tl_id" wire:change="filterSubWriters" name="writer" id="writer" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select Writer Name</option>
                                    @foreach($data['tl'] as $tl)
                                        <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterSubWriter" name="SubWriter" id="SubWriter" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select Sub Writer</option>
                                    @foreach($data['writers'] as $writer)
                                        <option value="{{ $writer->id }}">{{ $writer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterExtra" name="extra" id="extra" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Tech Resit Failed Job" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value="">Extra</option>
                                    <!-- Option for Tech -->
                                    <option value="tech" {{ old('extra') == 'tech' ? 'selected' : '' }}>Tech</option>
                                    <!-- Option for Resit -->
                                    <option value="resit" {{ old('extra') == 'resit' ? 'selected' : '' }}>Resit</option>
                                    <!-- Option for Failed Job -->
                                    <option value="1" {{ old('extra') == '1' ? 'selected' : '' }}>First Class Work</option>
                                    <option value="failedjob">Failed Job</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterStatus" name="status" id="status" class="form-select form-select-solid form-select-lg" data-control="select2">
                                    <option value="">Select Status</option>
                                    <option value="Not Assigned">Not Assigned</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Draft Ready">Draft Ready</option>
                                    <option value="Draft Feedback">Draft Feedback</option>
                                    <option value="Attached to Email (Draft)">Attached to Email (Draft)</option>
                                    <option value="Draft Delivered">Draft Delivered</option>
                                    <option value="Complete file Ready">Complete file Ready</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Feedback Delivered">Feedback Delivered</option>
                                    <option value="Attached to Email (Complete file)">Attached to Email (Complete file)</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Hold">Hold</option>
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterEditedOn" name="edited_on" id="edited_on" class="form-select form-select-solid form-select-lg">
                                    <option value="">Date Type</option>
                                    <option value="writer_deadline">Deadline</option>
                                    <option value="draft_date">Draft Date</option>
                                </select>
                            </div>

                            <div class="col-md-3 fv-row">
                                <input wire:model="filterFromDate" type="date" name="fromDate" id="fromDate" class="form-control form-control-solid form-select-lg" placeholder="From Date">
                            </div>

                            <div class="col-md-3 fv-row">
                                <input wire:model="filterToDate" type="date" name="toDate" id="toDate" class="form-control form-control-solid form-select-lg" placeholder="To Date">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <input type="text" name="dates" value="01/01/2024 - 01/15/2024" class="form-control form-control-solid form-select-lg"/>

                                <script>
                                    $('input[name="dates"]').daterangepicker();
                                </script>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <button type="button" wire:click="resetFilters" class="btn btn-sm btn-danger">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SR NO</th>
                                    <th>Order Code</th>
                                    <th>Delivery From - Upto</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Words</th>
                                    <th>Writer&TL</th>
                                    <th>Deadline</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['order'] as $index => $order)
                                <tr>
                                    <td>{{ ($data['order'] ->currentPage() - 1) * $data['order'] ->perPage() + $loop->index + 1 }} </td>
                                    <td>
                                        {{$order->order_id}}
                                        @if($order->is_fail == 1)
										<br><div class="label label-table label-danger m-1">Fail Order</div>
										@endif
										@if ($order->resit == 'on')
										<br><div class="label label-table label-danger m-1">Resit</div>
										@endif
										@if($order->services == 'First Class Work')
										<br><div class="label label-table label-info m-1">First Class
											Work</div>
										@endif
                                    </td>
                                    <td>
                                        @if ($order->writer_fd && $order->writer_fd != '0000-00-00')
                                            <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_fd)->format('jS M ') }} <div style="color:red;  margin-left: 10px;">{{date('h:i A', strtotime($order->writer_fd_h))}}</div> </div> <br>
                                            <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_ud)->format('jS M ') }} <div style="color:red;  margin-left: 10px;">{{date('h:i A', strtotime($order->writer_ud_h))}}</div></div>
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{$order->title }}
										@if( $order->chapter != '' )
										<div class="label label-table label-danger">{{$order->chapter}}</div>
										@endif

										@if( $order->tech == '1' )
										<div class="label label-table label-success">Technical Work</div>
										@endif
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
                                                    @if($order->subwriter && $order->subwriter->name)
                                                        {{$order->subwriter->name}}
                                                    @endif
                                                </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($order->writer_deadline)->format('jS M Y') }}
                                        @if($order->draftrequired == 'Y')
											<br>
											<div class="label label-table label-info">
												( 
													@if($order->draft_date && $order->draft_date != '0000-00-00')
														{{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }}
													@endif
													@if($order->draft_time && $order->draft_time != '00:00:00')
														{{ \Carbon\Carbon::parse($order->draft_time)->format('g:i A') }}
													@endif
												)
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
                                                        @if(session()->has('message'))
                                                            <div class="alert alert-success alert-dismissible" id="alert">
                                                                {{ session()->get('message') }}
                                                            </div>
                                                        @endif
                                                        <style>
                                                            #alert {
                                                            animation: fadeOut 2s forwards;
                                                            animation-delay: 1s;
                                                            }
                                                            @keyframes fadeOut {
                                                            to {
                                                                opacity: 0;
                                                                visibility: hidden;
                                                            }
                                                            }
                                                        </style>
                                                        <script>
                                                            setTimeout(function() {
                                                                document.getElementById("alert").style.display = "none";
                                                            }, 5000); // 5000ms = 5 seconds
                                                        </script>
                                                        <div class="row g-9 text-center">
                                                            <div class="col pb-2">
                                                                <div class="btn w-100 btn-outline-secondary p-2">{{ $order->order_id }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-flex align-items-center">
                                                            <div class="col-md-12">
                                                                <div class="form-group has-success">
                                                                    <select wire:model="tl_id_edit" wire:change="SubWritersEdit" class="form-control form-select mt-3" id="writer-tl{{ $order->id }}">
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
                                                                <div class="dropdown">
                                                                    <button style="width: 100%;" class="btn btn-success dropdown-toggle" type="button" id="multiSelectDropdown{{ $order->order_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        Select
                                                                    </button>
                                                                    <ul style="width: 100%; max-height: 30vh; overflow-y: auto;" class="dropdown-menu striped-list" aria-labelledby="multiSelectDropdown{{ $order->order_id }}">
                                                                        @foreach($data['writers2'] as $writer)
                                                                            @php
                                                                                $user_ids = $order->multiple ? $order->multiple->pluck('user_id')->toArray() : [];
                                                                                $isChecked = in_array($writer->id, $user_ids) ? 'checked' : '';
                                                                            @endphp
                                                                            <li>
                                                                                <label>
                                                                                    <input type="checkbox" wire:model="selectedWriters" value="{{ $writer->id }}" class="order-checkbox" data-order-id="{{ $order->order_id }}" {{ $isChecked }}>
                                                                                    {{ $writer->name }}
                                                                                </label>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <style>
                                                            .striped-list li:nth-child(odd) {
                                                                background-color: #A9CCE3;
                                                            }

                                                            .striped-list li:nth-child(even) {
                                                                background-color: #e9ecef;
                                                            }
                                                        </style>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                const chBoxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');
                                                                chBoxes.forEach((checkbox) => {
                                                                    checkbox.addEventListener('change', handleCB);
                                                                });

                                                                function handleCB(event) {
                                                                    const orderId = event.target.getAttribute('data-order-id');
                                                                    const dpBtn = document.getElementById('multiSelectDropdown' + orderId);
                                                                    const selectedItems = [];
                                                                    document.querySelectorAll('.order-checkbox[data-order-id="' + orderId + '"]').forEach((checkbox) => {
                                                                        if (checkbox.checked) {
                                                                            selectedItems.push(checkbox.parentElement.textContent.trim());
                                                                        }
                                                                    });

                                                                    // Limit the displayed text length to avoid overflow
                                                                    const maxLength = 50; // Adjust as needed
                                                                    dpBtn.innerText = selectedItems.length > 0 ? truncate(selectedItems.join(', '), maxLength) : 'Select';
                                                                }

                                                                // Function to truncate text
                                                                function truncate(text, maxLength) {
                                                                    return text.length > maxLength ? text.substring(0, maxLength - 3) + '...' : text;
                                                                }

                                                                // Function to reset TL dropdown and update writer button text when modal closes
                                                                function resetTLDropdown(modalId) {
                                                                    const modal = document.getElementById(modalId);
                                                                    if (modal) {
                                                                        const tlDropdown = modal.querySelector('select[id^="writer-tl"]');
                                                                        if (tlDropdown) {
                                                                            tlDropdown.value = "";
                                                                            // Trigger Livewire method to reset tl_id
                                                                            @this.call('resetTLId');
                                                                        }
                                                                        modal.querySelectorAll('.dropdown-menu input[type="checkbox"]').forEach((checkbox) => {
                                                                            checkbox.checked = false;
                                                                        });
                                                                        document.querySelectorAll('.order-checkbox[data-order-id="' + modalId + '"]').forEach((checkbox) => {
                                                                            handleCB({ target: checkbox });
                                                                        });
                                                                    }
                                                                }

                                                                // Attach event listener to modal close event
                                                                document.querySelectorAll('.modal').forEach((modal) => {
                                                                    modal.addEventListener('hidden.bs.modal', function () {
                                                                        resetTLDropdown(modal.id);
                                                                    });
                                                                });

                                                                // Function to clear selected writers when TL changes
                                                                function clearWritersOnTLChange() {
                                                                    document.querySelectorAll('.dropdown-menu input[type="checkbox"]').forEach((checkbox) => {
                                                                        checkbox.checked = false;
                                                                    });
                                                                    chBoxes.forEach((checkbox) => handleCB({ target: checkbox }));
                                                                }

                                                                // Attach event listener to TL dropdown change event
                                                                const tlDropdowns = document.querySelectorAll('select[id^="writer-tl"]');
                                                                tlDropdowns.forEach((tlDropdown) => {
                                                                    tlDropdown.addEventListener('change', clearWritersOnTLChange);
                                                                });
                                                            });
                                                        </script>

                                                        <div class="row d-flex align-items-center">
                                                            <div class="col-md-12">
                                                                <div class="form-group has-success">
                                                                    <select wire:model="status" class="form-control form-select mt-3" id="status{{ $order->id }}">
                                                                        <option {{ $order->writer_status == '' ? 'selected' : '' }} value="">Select Status</option>
																		<option {{ $order->writer_status == 'In progress' ? 'selected' : '' }} value="In progress">In Progress</option>
																		<option {{ $order->writer_status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
																		<option {{ $order->writer_status == 'Delivered' ? 'selected' : '' }} value="Delivered">Delivered</option>
																		<option {{ $order->writer_status == 'Hold' ? 'selected' : '' }} value="Hold">Hold</option>
																		<option {{ $order->writer_status == 'Draft Delivered' ? 'selected' : '' }} value="Draft Delivered">Draft Delivered</option>
																		<option {{ $order->writer_status == 'Feedback' ? 'selected' : '' }} value="Feedback">Feedback</option>
																		<option {{ $order->writer_status == 'Feedback Delivered' ? 'selected' : '' }} value="Feedback Delivered">Feedback Delivered</option>
																		<option {{ $order->writer_status == 'Quality Accepted' ? 'selected' : '' }} value="Quality Accepted">Quality Accepted</option>
																		<option {{ $order->writer_status == 'Quality Rejected' ? 'selected' : '' }} value="Quality Rejected">Quality Rejected</option>
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
                                                        <button type="submit" data-bs-dismiss="modal" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
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
