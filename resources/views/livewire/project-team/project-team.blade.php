<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">OrderProject</h4>
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
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fs-5 mb-1">Filter</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form wire:submit.prevent="applyFilters">
                        <div class="row mb-3">
                            <div class="col-md-3 fv-row">
                                <input wire:model="search" type="search"  name="search" id="search" class="form-control form-control-solid form-select-lg" placeholder="OrderCode, Title" >
                            </div>     

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select wire:model="filterByStatus" name="status" id="status" aria-label="Select a Language" data-control="select2" data-placeholder="Status" class="form-control form-control-solid form-select-lg" data-select2-id="select2-data-13-mh4q" tabindex="-1" >
                                    <option value="" >Select Status</option>
                                    <option  value="Not Assign">Not Assign</option>
                                    <option value="In Progress" >In Progress</option>
                                    <option value="Draft Ready" >Draft Ready</option>
                                    <option value="Draft Feedback" >Draft Feedback</option>
                                    <option value="Attached to Email (Draft) " >Attached to Email (Draft) </option>
                                    <option value="Draft Delivered">Draft Delivered</option>
                                    <option value="Complete file Ready">Complete file Ready</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Feedback Delivered">Feedback Delivered</option>
                                    <option value="Attached to Email (Complete file) ">Attached to Email (Complete file) </option>
                                    <option value="Delivered" >Delivered</option>
                                    <option value="Hold">Hold</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select wire:model="filterSubWriter" name="SubWriter" id="SubWriter" aria-label="Select a Timezone" data-placeholder="Search By Writer TL" class="form-control form-control-solid form-select-lg" tabindex="-1">
                                    <option value="">Select Writer Name</option>
                                    
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select wire:model="filterExtra" name="extra" id="extra" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Tech Resit Failed Job" class="form-control form-control-solid form-select-lg" tabindex="-1">
                                    <option value="">Extra</option>
                                    <!-- Option for Tech -->
                                    <option value="tech" >Tech</option>
                                    <!-- Option for Resit -->
                                    <option value="resit" >Resit</option>
                                    <!-- Option for Failed Job -->
                                    <option value="1" >First Class Work</option>
                                    <option value="failedjob">Failed Job</option>
                                </select>
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
                                    <th>Order Code</th>
                                    <th>Order Date</th>
									<th>Delivery Date <br> (Draft D/T)</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>TL&Writer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['orders'] as $index => $order)
								<tr @if( $order->user_is_fail == 1) style="color:blue"  @endif>
									<td class="text-center">{{ ($data['orders'] ->currentPage() - 1) * $data['orders'] ->perPage() + $loop->index + 1 }}</td>
									
                                    <td class="text-center">
										{{ $order->order_id }}
										@if($order->is_fail == 1)
										<br><div class="label label-table label-danger m-1">Fail Order</div>
										@endif
										@if ($order->resit == 'on')
										<br><div class="label label-table label-danger m-1">Resit</div>
										@endif
										@if($order->services == 'First Class Work')
										<br><div class="label label-table label-info m-1">First Class Work</div>
										@endif
									</td>
									<td>
                                        @if($order->order_date && $order->order_date != '0000-00-00')
                                            <div class="label label-table label-info m-1">{{ \Carbon\Carbon::parse($order->order_date)->format('jS M ') }} </div>                                        
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->delivery_date && $order->delivery_date != '0000-00-00')
                                            <div class="label label-table label-info m-1">{{ \Carbon\Carbon::parse($order->delivery_date)->format('jS M ') }} </div>                                        
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @endif
                                        @if( $order->draftrequired == 'Y')
                                            <br>
                                            <div class="label label-table label-primary m-1">
                                                @if($order->draft_date && $order->draft_date != '0000-00-00')
                                                    {{ \Carbon\Carbon::parse($order->draft_date)->format('jS M ') }} 
                                                @endif
                                                @if($order->draft_time)
                                                    <br>({{date('h:i A', strtotime($order->draft_time))}})
                                                @endif
                                            </div>                                        
                                        @endif	
                                    </td>                                    									
                                    <td>
                                        {{$order->title }}
										@if( $order->chapter != '' )
										    <br><div class="label label-table label-danger">{{$order->chapter}}</div>
										@endif
										@if( $order->tech == '1' )
										    <br><div class="label label-table label-success">Technical Work</div>
										@endif
                                        @if ($order->module_code != '')
                                            <br><div class="label label-table label-danger">{{$order->module_code}}</div>
                                        @endif
									</td>
									<td>                                        
                                        @if($order->projectstatus == 'Other')
                                            <span class="label label-table label-primary fs-7 fw-bold " style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Pending')
                                            <span class="label label-table label-warning fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'In Progress')
                                            <span class="label label-table label-info m-1 fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Hold Work')
                                            <span class="label label-table label-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Completed')
                                            <span class="badge badge-light-warning fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Delivered')
                                            <span class="badge badge-light-success fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Feedback')
                                            <span class="badge badge-light-primary fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Feedback Delivered')
                                            <span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Cancelled')
                                            <span class="label label-table label-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Draft Ready')
                                            <span class="badge badge-light-primary fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Draft Delivered')
                                            <span class="badge badge-light-primary fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Initiated')
                                            <span class="badge badge-light-primary fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Advance Assignment')
                                            <span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                        @else
                                            {{ $order->projectstatus }}
                                        @endif
									</td>
									
								    <td>                                        
                                        @if($order->writer_name != null)
                                            <div>
                                                {{ $order->writer_name }}
                                                <br>
                                                <span class="label label-table label-info fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}</span>	
                                            </div>
                                        @else
                                            <div class="label label-table label-danger">Not Assigned</div>
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
                        {{ $data['orders']->links() }}
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
                                <div class="col pb-4">
                                    <div class="btn w-100 btn-outline-secondary p-2">{{ $orderCode }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" wire:model="status" class="form-control">
                                    <option value="Not Assigned">Not Assigned</option>
                                    <option value="Draft Ready">Draft Ready</option>
                                    <option value="Complete file Ready">Complete file Ready</option>
                                    
                                </select>
                            </div>
                            

                            <button type="button" wire:click.prevent="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
