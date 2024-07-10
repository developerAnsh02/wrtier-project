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
                }, 15000);
            </script>
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header d-flex">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fs-5 mb-1">Filter</span>
                    </h3>
                    <!-- Preloader (optional) -->
                    <div wire:loading wire:target="applyFilters"style="width: 100%; text-align: center;">                            
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>                            
                    </div>
                </div>
                <div class="card-body py-3">
                    <form wire:submit.prevent="applyFilters">
                        <div class="row mb-3">
                            <div class="col-md-3 fv-row">
                                <input wire:model="search" type="search"  name="search" id="search" class="form-control form-control-solid form-select-lg" placeholder="OrderCode, Title" >
                            </div>     

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select wire:model="filterByStatus" name="status" id="status" aria-label="Select a Language" data-control="select2" placeholder="Status" class="form-control form-control-solid form-select-lg" data-select2-id="select2-data-13-mh4q" tabindex="-1" >
                                    <option value="" >Select a status</option>                        
                                    @foreach($data['Status'] as $Status)
                                        <option value="{{$Status->status}}">{{$Status->status}}</option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select wire:model="filterByTeam" name="team" id="team" aria-label="Select a Timezone" placeholder="Search By Team" class="form-control form-control-solid form-select-lg" tabindex="-1">
                                    <option value="" >Select a Team</option>                        
                                    @foreach($data['Team'] as $writer)
                                        <option value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
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
                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterEditedOn" name="edited_on" id="edited_on" class="form-select form-select-solid form-select-lg">
                                    <option value="">Date Type</option>
                                    <option value="order_date">Order Date</option>
                                    <option value="delivery_date">Delivery Date</option>
                                    <option value="writer_deadline">Writer Deadline</option>
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
                                    <th>Writer</th>
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
        <div class="modal fade show" style="display: block;  scrollbar-width: none;">
            <div class="modal-dialog" style="max-width: 900px;">
                <div class="modal-content" style="background: aliceblue;">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Order</h5>
                        <button type="button" wire:click="closeEditModal" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-4" style="border: 1px solid gray;">
                        <form>
                            <div class="row g-9 text-center mt-4">
                                <div class="col pb-4">
                                    <div class="btn w-100 btn-outline-secondary p-2">{{ $orderCode }}</div>
                                </div>
                            </div>
                            <div class="row g-9 mb-2 text-start">
                                <div class="col-md-4 mx-auto fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Module Code</label>
                                    <input type="text" wire:model="module_code" required class="form-control form-control-solid" placeholder="" value="" name="module_code">
                                    @error('module_code')
                                        <div style="color:red;" class="mt-2 auto-hide">*{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8 mx-auto fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Project Title</label>
                                    <input type="text" wire:model="project_title" required class="form-control form-control-solid" placeholder="" value="" name="title">
                                    @error('project_title')
                                        <div style="color:red;" class="mt-2 auto-hide">*{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-9 mb-2 text-start">
                                <div class="col-md-4 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Order Date</label>
                                    <input type="date" wire:model="order_date" class="form-control form-control-solid" placeholder="" value="" name="order_date">
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Writer Deadline</label>
                                    <input type="date" wire:model="writer_deadline" class="form-control form-control-solid" placeholder="" value="" name="writer_deadline">
                                </div>
                                <div class="col-md-4 fv-row text-start">
                                    <label class="fs-6 fw-bold mb-2">Writer Deadline Time</label>                                   
                                    <input type="time" wire:model="writer_deadline_time" class="form-control form-control-solid" placeholder="" value="" name="writer_deadline_time">                                    
                                </div>
                            </div>
                            <div class="row g-9 mb-2 text-start">
                                <div class="col-md-4 fv-row text-start">
                                    <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                                    <input type="date" wire:model="delivery_date" class="form-control form-control-solid" placeholder="" value="" readonly>
                                </div>
                                <div class="col-md-4 fv-row text-start">
                                    <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                                    @if($delivery_time)
                                    <input type="time" wire:model="delivery_time" class="form-control form-control-solid" placeholder="" value="" readonly>
                                    @else
                                    <input type="time" class="form-control form-control-solid" placeholder="" value="" readonly>
                                    @endif
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                                    <select wire:model="type_of_paper" name="paper" class="form-control form-control-solid" disabled>
                                        <option value="" data-select2-id="select2-data-18-e9lh12">Not Selected</option>
                                        @foreach($AllPaper as $paper)
                                            <option value="{{$paper->paper_type}}">{{$paper->paper_type}}</option>
                                        @endforeach   
                                    </select>                         
                                </div>
                            </div>                            
                            <div class="row mb-2">
                                <div class="col-md-4 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Chapter</label>
                                    <select wire:model="chapter" name="chapter" class="form-control form-control-solid">
                                        <option value=""> </option>
                                        <option value="Chapter 1:  Introduction">Chapter 1:  Introduction</option>
                                        <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
                                        <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
                                        <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
                                        <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
                                    </select>    
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Project Status</label>
                                    <select wire:model="status" class="form-control">
                                        <option value=""></option>
                                        @foreach($AllStatus as $status)
                                        <option value="{{$status->status}}">{{$status->status}}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div style="color:red;" class="mt-2 auto-hide">*{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Writer Name</label>
                                    <select wire:model="writer_name" class="form-control">
                                        <option value=""></option>
                                        @foreach($AllTeam as $writer)
                                        <option  value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-9 mb-2 text-start">       
                                <div class="col-md-4 fv-row text-start">
                                    <label class=" fs-6 fw-bold mb-2">Word</label>
                                    <input type="text" wire:model="word" class="form-control form-control-solid" placeholder="" value="" name="word">
                                    @error('word')
                                        <div style="color:red;" class="mt-2 auto-hide">*{{ $message }}</div>
                                    @enderror
                                </div>                         
                            </div>                         
                            <div class="row g-9 mb-2 text-start">                                
                                <div class="col-md-4 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Draft Required</label>
                                    <select wire:model="draft_required" class="form-control form-control-solid">
                                        <option value=""></option>
                                        <option value="N">No</option>
                                        <option value="Y">Yes</option>
                                    </select>                         
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Draft Date</label>
                                    <input wire:model="draft_date" type="date" class="form-control form-control-solid" name="draft_date">
                                </div>                            
                                <div class="col-md-4 fv-row text-start">
                                    <label class="fs-6 fw-bold mb-2">Draft Time</label>
                                    <input wire:model="draft_time" type="time" class="form-control form-control-solid">                                    
                                </div>                                
                            </div>
                            <div class="row g-9 mb-4 text-center">
                                <div class="col-md-12 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Messages</label>
                                    <textarea wire:model="messages" name="messages" value="" class="form-control form-control-solid" id="" cols="30" rows="3"></textarea>
                                </div>
                            
                            </div>
                            <div class="row g-9 mb-2 text-end">
                                <div class="col-md-12 fv-row">
                                    <button type="button" wire:click.prevent="update" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>                        
                        <!-- Preloader (optional) -->
                        <div wire:loading wire:target="update"style="width: 100%; text-align: center;">                            
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new MutationObserver(function (mutationsList, observer) {
                mutationsList.forEach(function (mutation) {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(function (node) {
                            if (node.classList && node.classList.contains('auto-hide') && !node.classList.contains('processed')) {
                                setTimeout(function () {
                                    node.style.display = 'none';
                                    node.classList.add('processed');
                                }, 5000); // 5000 milliseconds = 5 seconds
                            }
                        });
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script> -->
</div>
