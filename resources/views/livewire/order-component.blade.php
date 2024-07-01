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
                                    @foreach($newWriter as $writer)
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
                        <div class="row mb-3">                        
                            <div class="col-md-3 fv-row">
                                <input  type="text" name="dates" value="" class="form-control form-control-solid form-select-lg"/>
                                <script>
                                    $('input[name="dates"]').daterangepicker({
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        }
                                    }, function(start, end, label) {
                                        @this.set('filterFromDateRange', `${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                    });
                                </script>
                            </div>
                            <div class="col-md-3 fv-row">
                                <input type="text" name="Todates" value="" class="form-control form-control-solid form-select-lg"/>
                                <script>
                                    $('input[name="Todates"]').daterangepicker({
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        }
                                    }, function(start, end, label) {
                                        @this.set('filterToDateRange', `${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                    });
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
                                        <div class="d-flex">
                                            @if($order->writer_fd && $order->writer_fd != '0000-00-00')
                                                <div>{{ \Carbon\Carbon::parse($order->writer_fd)->format('jS M ') }} </div>                                        
                                            @endif
                                            @if($order->writer_ud && $order->writer_ud != '0000-00-00')
                                                <div>{{ \Carbon\Carbon::parse($order->writer_ud)->format('jS M ') }} </div>                                        
                                            @endif
                                        </div>
                                        <div class="d-flex">
                                            @if($order->writer_fd_h == 'First Half' || $order->writer_fd_h == 'Second Half')
                                            <div style="color:red;">{{ $order->writer_fd_h }}</div> 
                                            @elseif($order->writer_fd_h && $order->writer_fd_h != '')
                                            <div style="color:red;">{{date('h:i A', strtotime($order->writer_fd_h))}}</div>                                        
                                            @endif
                                            @if($order->writer_ud_h == 'First Half' || $order->writer_ud_h == 'Second Half')
                                            <div style="color:red;">{{ $order->writer_ud_h }}</div> 
                                            @elseif($order->writer_ud_h && $order->writer_ud_h != '')
                                            <div style="color:red;">{{date('h:i A', strtotime($order->writer_ud_h))}}</div>                                        
                                            @endif
                                        </div>
                                        @if($order->writer_fd == '' && $order->writer_ud == '' && $order->writer_fd_h == '' && $order->writer_ud_h == '')
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
                                    
                                    <td class="text-center">
                                        <button wire:click="edit({{ $order->id }})" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li class="fa fa-edit"></li>
                                            </span>
                                        </button>
									</td>
                                </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data['order']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($isEditModalOpen)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Order</h5>
                        <button type="button" wire:click="closeEditModal" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row g-9 text-center">
                                <div class="col pb-2">
                                    <div class="btn w-100 btn-outline-secondary p-2">{{ $orderCode }}</div>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-md-12">
                                    <div class="form-group has-success">
                                        <select wire:model="tl_id_edit" wire:change="SubWritersEdit" class="form-control form-select mt-3" id="writer-tl{{ $order->id }}">
                                            <option value="">Select TL</option>
                                            @foreach($modalTL as $tl)
                                            <option value="{{ $tl->id }}" wire:key="tl_id_edit" >{{ $tl->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tl_id_edit') <span class="text-danger">{{ $message }}</span> @enderror
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
                                            @if(!$tl_id_edit)
                                                <li>
                                                    <label>
                                                        <input type="checkbox" value="" class="order-checkbox">
                                                        Select a TL for writer
                                                    </label>
                                                </li>
                                            @endif
                                            @foreach($modalWriter as $writer)                                                
                                                <li>
                                                    <label>
                                                        <input type="checkbox" wire:model="selectedWriters" value="{{ $writer->id }}" class="order-checkbox" data-order-id="{{ $order->order_id }}">
                                                        {{ $writer->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('selectedWriters') <span class="text-danger">{{ $message }}</span> @enderror
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
                    
                            <div class="form-group mt-3">
                                <label for="status">Status</label>
                                <select id="status" wire:model="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="Not Assigned">Not Assigned</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Draft Ready">Draft Ready</option>
                                    <option value="Draft Delivered">Draft Delivered</option>
                                    <option value="Complete file Ready">Complete file Ready</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Hold">Hold</option>                                    
                                </select>
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
                            <button type="button" wire:click.prevent="updateOrder" class="btn btn-primary">Update</button>                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

</div>
