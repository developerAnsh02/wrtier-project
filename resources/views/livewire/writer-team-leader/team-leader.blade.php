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
                                    @foreach($data['SubWriter'] as $Subwriter)
                                        @if($Subwriter->tl_id == Auth::user()->id)
                                        <option value="{{ $Subwriter->id }}">{{ $Subwriter->name }}</option>
                                        @endif
                                    @endforeach
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
                        <div class="row mb-3">
                        
                            <div class="col-md-3 fv-row">
                                <input type="text" name="dates" value="01/01/2024 - 01/15/2024" class="form-control form-control-solid form-select-lg"/>

                                <script>
                                    $('input[name="dates"]').daterangepicker();
                                </script>
                            </div>
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
                                <!-- <button type="button" wire:click="resetFilters" class="btn btn-sm btn-danger">Reset</button> -->
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
                                    <th>TL&Writer</th>
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
                            <div class="dropdown mb-3">
                                <label for="mulsubwriter" class="form-label">Subwriters</label>
                                <button type="button" class="form-control">Select Subwriters</button>
                                <div class="dropdown-content p-2">
                                    @foreach($subWriters as $subWriter)
                                    <label><input type="checkbox" name="mulsubwriter[]" value="{{ $subWriter->id }}" wire:model="mulsubwriter"> {{ $subWriter->name }}</label>
                                    @endforeach
                                </div>
                                @error('mulsubwriter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <style>
                            .dropdown {
                            position: relative;
                            display: inline-block;
                            }

                            .dropdown-content {
                            display: none;
                            position: absolute;
                            background-color: #f9f9f9;
                            min-width: 160px;
                            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                            z-index: 1;
                            }

                            .dropdown-content label {
                            display: block;
                            margin-top: 10px;
                            }

                            .dropdown:hover .dropdown-content {
                            display: block;
                            }
                            </style>
                            <script>
                            document.addEventListener('click', function(event) {
                            var dropdownContent = document.querySelector('.dropdown-content');
                            var dropdownButton = document.querySelector('.dropdown button');
                            if (!dropdownContent.contains(event.target) && !dropdownButton.contains(event.target)) {
                                dropdownContent.style.display = 'none';
                            } else {
                                dropdownContent.style.display = 'block';
                            }
                            });

                            function getSelectedSubwriters() {
                            var checkboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]');
                            var selectedSubwriters = [];
                            checkboxes.forEach(function(checkbox) {
                                if (checkbox.checked) {
                                selectedSubwriters.push(checkbox.value);
                                }
                            });
                            return selectedSubwriters;
                            }

                            // Example usage
                            document.querySelector('.dropdown button').addEventListener('click', function() {
                            console.log(getSelectedSubwriters());
                            });
                            </script>

                            <button type="button" wire:click.prevent="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
