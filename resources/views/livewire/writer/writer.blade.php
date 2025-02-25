<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Writer Order</h4>
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
                            
                            <div class="col-md-3 fv-row">
                                <input  type="text" name="dates" value="" placeholder="Select From-Date Range" class="form-control form-control-solid form-select-lg"/>
                                <script>
                                    var currentDate = new Date();
                                    var currentMonthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                                    var currentMonthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                                    $('input[name="dates"]').daterangepicker({
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        },
                                        startDate: currentMonthStart,
                                        endDate: currentMonthEnd,
                                        autoUpdateInput: false,
                                    }, function(start, end, label) {
                                        $('input[name="dates"]').val(`${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                        @this.set('filterFromDate', `${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                    });
                                    $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
                                        $(this).val('');
                                    });   
                                                           
                                </script>
                            </div>
                        </div>
                        <div class="row mb-3">                        
                            <div class="col-md-3 fv-row">
                                <input type="text" name="Todates" value="" placeholder="Select To-Date Range" class="form-control form-control-solid form-select-lg"/>
                                <script>
                                    var currentDate = new Date();
                                    var currentMonthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                                    var currentMonthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                                    $('input[name="Todates"]').daterangepicker({
                                        locale: {
                                            format: 'DD/MM/YYYY'
                                        },
                                        startDate: currentMonthStart,
                                        endDate: currentMonthEnd,
                                        autoUpdateInput: false,
                                    }, function(start, end, label) {
                                        $('input[name="Todates"]').val(`${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                        @this.set('filterToDate', `${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                                    });
                                    $('input[name="Todates"]').on('cancel.daterangepicker', function(ev, picker) {
                                        $(this).val('');
                                    });
                                    
                                </script>
                            </div>                        
                        </div>
                        <script>
                            window.resetDateRangePickers = function () {
                                var currentDate = new Date();
                                var currentMonthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                                var currentMonthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

                                $('input[name="Todates"], input[name="dates"]').val('');
                                $('input[name="Todates"], input[name="dates"]').data('daterangepicker').setStartDate(currentMonthStart);
                                $('input[name="Todates"], input[name="dates"]').data('daterangepicker').setEndDate(currentMonthEnd);
                            };
                        </script>
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <button type="button" @click="$wire.call('resetFilters').then(() => resetDateRangePickers())" class="btn btn-sm btn-danger">Reset</button>
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
                                    <th>Delivery From - Upto</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Words</th>
                                    <th>Comments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['orders'] as $index => $order)
								<tr>
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
                                        <div class="d-flex">
                                            @if($order->writer_fd && $order->writer_fd != '0000-00-00')
                                                <div class="label label-table label-info m-1">{{ \Carbon\Carbon::parse($order->writer_fd)->format('jS M ') }} </div>                                        
                                            @endif
                                            @if($order->writer_ud && $order->writer_ud != '0000-00-00')
                                                <div class="label label-table label-danger m-1">{{ \Carbon\Carbon::parse($order->writer_ud)->format('jS M ') }} </div>                                        
                                            @endif
                                        </div>
                                        <div class="d-flex">
                                            @if($order->writer_fd_h == 'First Half' || $order->writer_fd_h == 'Second Half')
                                            <div class="label label-table label-info m-1">{{ $order->writer_fd_h }}</div> 
                                            @elseif($order->writer_fd_h && $order->writer_fd_h != '')
                                            <div class="label label-table label-info m-1">{{date('h:i A', strtotime($order->writer_fd_h))}}</div>                                        
                                            @endif
                                            @if($order->writer_ud_h == 'First Half' || $order->writer_ud_h == 'Second Half')
                                            <div class="label label-table label-danger m-1">{{ $order->writer_ud_h }}</div> 
                                            @elseif($order->writer_ud_h && $order->writer_ud_h != '')
                                            <div class="label label-table label-danger m-1">{{date('h:i A', strtotime($order->writer_ud_h))}}</div>                                        
                                            @endif
                                        </div>
                                        @if($order->writer_fd == '' && $order->writer_ud == '' && $order->writer_fd_h == '' && $order->writer_ud_h == '')
                                            <div class="label label-table label-danger">Not Assigned</div>
                                        @elseif($order->writer_fd == '0000-00-00' || $order->writer_ud == '0000-00-00')
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

									<td>{{$order->pages}}</td>
                                    
                                    <td>
                                        @php
                                            $latestComment = $order->comments->where('is_deleted', false)->sortByDesc('created_at')->first();
                                        @endphp
                                        <div>
                                            @if ($latestComment)
                                                {{ Str::limit($latestComment->comment, 50) }}  
                                                <small class="text-muted">({{ $latestComment->user->name }})</small>
                                            @else
                                                <span class="text-muted">No comments yet</span>
                                            @endif
                                        </div>

                                        <!-- View Comments Button -->
                                        <button wire:click="viewComments({{ $order->id }})" class="btn btn-sm btn-info mt-2">
                                            View Comments
                                        </button>
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
                                    <option value=""></option>
                                    <option value="Hold">Hold</option>
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

    <!-- Comments Modal -->
    @if($isCommentModalOpen)
        <div class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comments for Order #{{ $selectedOrderId }}</h5>
                        <button type="button" wire:click="closeCommentModal" class="btn-close"></button>
                    </div>
                    
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse ($comments as $comment)
                            <div class="mb-3 p-3 border rounded shadow-sm" style="background: #f1f0f0;">
                                <p class="mb-1">{{ $comment->comment }}</p>
                                <small class="text-muted d-block">
                                    {{ $comment->created_at->format('d M, Y h:i A') }} 
                                    - <strong>{{ $comment->user->name }}</strong>
                                </small>
                            </div>
                        @empty
                            <p class="text-center text-muted">No comments available.</p>
                        @endforelse
                    </div>

                    <div class="modal-footer">
                        <textarea class="form-control" placeholder="You cannot add comments." disabled></textarea>
                        <button type="button" wire:click="closeCommentModal" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
