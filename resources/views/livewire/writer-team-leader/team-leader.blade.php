<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">OrderTL</h4>
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
                                    <th>Order Code</th>
                                    <th>Delivery From - Upto</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Words</th>
                                    <th>Writer&TL</th>
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

									<td>{{$order->pages}}</td>
									
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
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" wire:model="status" class="form-control">
                                    <option value="Not Assigned">Not Assigned</option>
                                    <option value="Draft Ready">Draft Ready</option>
                                    <option value="Complete file Ready">Complete file Ready</option>
                                    
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mulsubwriter" class="form-label">Multiple Subwriters</label>
                                <select class="form-control" id="mulsubwriter" wire:model="mulsubwriter" multiple>
                                    @foreach($subWriters as $subWriter)
                                        <option value="{{ $subWriter->id }}">{{ $subWriter->name }}</option>
                                    @endforeach
                                </select>
                                @error('mulsubwriter') <span class="text-danger">{{ $message }}</span> @enderror
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
