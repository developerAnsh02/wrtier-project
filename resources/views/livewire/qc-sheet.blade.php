<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">QC-Sheet</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">QC-Sheet</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- Display the orders -->
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
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <input wire:model.debounce.300ms="search" type="search" name="search" id="search" class="form-control form-control-solid form-select-lg" placeholder="Search OrderCode">
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterWriter" wire:change="filterSubWriters" name="writer" id="writer" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select Writer Name</option>
                                    @foreach($writers as $tl)
                                        <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterSubWriter" name="SubWriter" id="SubWriter" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select Sub Writer</option>
                                    @if ($filterWriter)
                                        @foreach($subWriters as $Sub)
                                            <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($subWriters as $Sub)
                                            <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row">
                                <select wire:model="filterQcStandard" name="qc_standard" id="qc_standard" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select QcStandard</option>
                                    <option value="poor">Poor</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="good">Good</option>
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
                                    <option value="Order-date">Order Date</option>
                                    <option value="Qc-date">QC Date</option>
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">                    
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

                    <div class="table-responsive table-card">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if( Auth::user()->role_id == 3 || Auth::user()->role_id == 8)
										<th>Checked</th>
                                    @endif
                                    <th>Sr. No</th>
                                    <th>Order Code</th>
                                    <th>D Date</th>
                                    <th>Status</th>
                                    <th>Quality standard</th>
                                    <th>Ai Score</th>
                                    <th>Writer </th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                <tr>
                                    @if( Auth::user()->role_id == 3 || Auth::user()->role_id == 8)
                                        <td class="text-center">                                            
                                            <div>
                                                <input type="checkbox" wire:click="toggleCheck({{ $order->id }})" {{ $order->qc_checked ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    @endif                                                                                                                    
                                    <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->index + 1 }}</td>                                    
                                    <td style="white-space: nowrap;">
                                        <div>{{ $order->order_id }}</div>                                        
                                        <div class="label label-table label-info">{{ $order->qc_admin }}</div>
                                    </td>
                                    <td style="white-space: nowrap;">                                        
                                        <div>{{ \Carbon\Carbon::parse($order->writer_deadline)->format('jS M Y') }}</div>                                            
                                        @if($order->qc_date != null)                                            
                                            <div class="label label-table label-danger">{{ \Carbon\Carbon::parse($order->qc_date)->format('jS M Y') }}</div>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($order->writer_status == 'Not Assigned')
                                            <div class="label label-table label-danger" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'In Progress')
                                            <div class="label label-table label-info" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Ready')
                                            <div class="label label-table label-danger" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Feedback' )
                                            <div class="label label-table label-warning" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Attached to Email (Draft)' )
                                            <div class="label label-table label-danger" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Draft Delivered' )
                                            <div class="label label-table label-success" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Complete file Ready' )
                                            <div class="label label-table label-warning" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Feedback' )
                                            <div class="label label-table label-warning" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Feedback Delivered' )
                                            <div class="label label-table label-warning" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Attached to Email (Complete file)' )
                                            <div class="label label-table label-info" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Delivered' )
                                            <div class="label label-table label-success" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status == 'Hold' )
                                            <div class="label label-table label-danger" > {{ $order->writer_status }}</div>
                                        @elseif($order->writer_status === '' || $order->writer_status === null)
                                            <div class="label label-table label-danger" >Not Assigned</div>
                                        @else
                                            <div class="label label-table label-danger" > {{ $order->writer_status }}</div>
                                        @endif
                                    
                                    </td>                                                                                       
                                    <td style="white-space: nowrap;">
                                        @if($order->qc_standard == 'poor')
                                            <div class="label label-table label-danger" style="background:#795548; color:white"> {{ $order->qc_standard }}</div>
                                        @elseif($order->qc_standard == 'Good')
                                            <div class="label label-table label-danger" style="background:#eaea00; color:black"> {{ $order->qc_standard }}</div>
                                        @elseif($order->qc_standard == 'moderate')
                                            <div class="label label-table label-danger" style="background:#4caf50; color:white"> {{ $order->qc_standard }}</div>
                                        @else
                                            <div class="label label-table label-danger"></div>
                                        @endif
                                    </td>
                                    <td id="ai-score">
                                        @if($order->ai_score != null)
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editAiScoreModal{{ $order->id }}" wire:click="editQc({{ $order->id }})" class="btn btn-sm btn-light-primary ml-2" title="Edit AI Score">{{ $order->ai_score }} %</button>                                                
                                        @else                                            
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editAiScoreModal{{ $order->id }}" wire:click="editQc({{ $order->id }})" class="btn btn-sm btn-light-primary ml-2" title="Edit AI Score"><div class="label label-table label-danger"></div></button>
                                        @endif
                                        
                                         <!-- Button to open AI Score modal -->

                                        <!-- Modal for AI Score -->
                                        <div wire:ignore.self class="modal fade" id="editAiScoreModal{{ $order->id }}" tabindex="-1" aria-labelledby="editAiScoreModalLabel{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form wire:submit.prevent="updateAiScore({{ $order->id }})">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAiScoreModalLabel{{ $order->id }}">Edit AI Score</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-9 text-center">
                                                                <div class="col pb-2">
                                                                    <div class="btn w-100 btn-outline-secondary p-2">{{ $order->order_id }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="ai_score_input{{ $order->id }}" class="form-label">AI Score</label>
                                                                <input type="text" wire:model.defer="ai_score_input" class="form-control" id="ai_score_input{{ $order->id }}" placeholder="Enter AI Score">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save AI Score</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if( $order['writer'] && !$order['writer']['name'] == "")
                                            @if ($order->mulsubwriter)                                                
                                                @foreach ($order->mulsubwriter as $writer)
                                                    @if($writer->user)
                                                        {{ $writer->user->name }} <br>
                                                    @endif
                                                @endforeach
                                            @else
                                                No writers found for this order.
                                            @endif
                                            <br>
                                            <div class="label label-table label-info">
                                                ({{$order['writer']['name'] }})
                                            </div>
                                        @else                                            
                                            <div class="label label-table label-danger">
                                                Not Assign
                                            </div>
                                        @endif                                                                                        
                                    </td>
                                    <td>
                                        @if($order->qc_comment != null)
                                            {{ $order->qc_comment }}
                                        @else
                                            <div class="label label-table label-danger"></div>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal{{$order->id}}" wire:click="editQc({{$order->id}})" class="btn btn-sm btn-light-primary"><li class="fa fa-edit"> </li></button>
                                    </td>
                                    
                                </tr>
                                <!-- Modal -->
                                    <div wire:ignore.self class="modal fade" id="editModal{{$order->id}}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form wire:submit.prevent="updateQc({{ $order->id }})">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit QC</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>               
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Add your form or content for editing here -->
                                                        <div class="row g-9 text-center">
                                                            <div class="col pb-2">
                                                                <div class="btn w-100 btn-outline-secondary p-2">{{ $order->order_id }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-9 mb-8 text-start">
                                                            <div class="col-md-6 fv-row">
                                                                <label class="fs-6 fw-bold mb-2">Qc Status</label>
                                                                <select wire:model.defer="qc_status" required="" name="qc_status" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                                                    <option value=""  data-select2-id="select2-data-18-e9lh12"></option>
                                                                    
                                                                    @if(auth()->user()->role_id == 3)
                                                                        <option value="Draft Feedback">Draft Feedback</option>
                                                                        <option value="Attached to Email (Draft)">Attached to Email (Draft) </option>
                                                                        <option value="Feedback">Feedback</option>
                                                                        <option value="Attached to Email (Complete file)">Attached to Email (Complete file) </option>
                                                                    @endif
                                                                    @if(auth()->user()->role_id == 8)
                                                                        <option value="Not Assigned">Not Assigned</option>
                                                                        <option value="In Progress">In Progress</option>
                                                                        <option value="Draft Ready">Draft Ready</option>
                                                                        <option value="Draft Delivered">Draft Delivered</option>
                                                                        <option value="Complete file Ready">Complete file Ready</option>
                                                                        <option value="Feedback">Feedback</option>
                                                                        <option value="Feedback Delivered">Feedback Delivered</option>
                                                                        <option value="Delivered">Delivered</option>
                                                                        <option value="Hold">Hold</option>
                                                                    @endif
                                                                    @if(auth()->user()->role_id == 6)
                                                                        <option value="Draft Ready">Draft Ready</option>
                                                                        <option value="Complete file Ready">Complete file Ready</option>
                                                                    @endif
                                                                </select>
                                        
                                                            </div>
                                                            <div class="col-md-6 fv-row">
                                                                <label class="fs-6 fw-bold mb-2">Qc standard</label>
                                                                <select wire:model.defer="qc_standard" required="" name="qc_standard" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                                                    <option value=""  data-select2-id="select2-data-18-e9lh12"></option>
                                                                    <option value="poor">poor</option>
                                                                    <option value="moderate">moderate</option>
                                                                    <option value="Good">Good</option>
                                                                </select>
                                                            </div>
<!-- <div class="dropdown"> 
    <button class="btn btn-success dropdown-toggle" 
            type="button" 
            id="multiSelectDropdown{{ $order->order_id }}" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"> 
        Select 
    </button> 
    <ul class="dropdown-menu striped-list" 
        aria-labelledby="multiSelectDropdown{{ $order->order_id }}"> 
        <li> 
          <label> 
            <input type="checkbox" 
                   value="Java" 
                   class="order-checkbox" 
                   data-order-id="{{ $order->order_id }}"> 
                Java 
            </label> 
        </li> 
        <li> 
          <label> 
            <input type="checkbox" 
                   value="C++" 
                   class="order-checkbox" 
                   data-order-id="{{ $order->order_id }}"> 
                C++ 
            </label> 
        </li> 
        <li> 
          <label> 
            <input type="checkbox" 
                   value="Python" 
                   class="order-checkbox" 
                   data-order-id="{{ $order->order_id }}"> 
                Python 
            </label> 
        </li> 
    </ul> 
</div> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> 

<style>
    .striped-list li:nth-child(odd) {
        background-color: #A9CCE3; /* Light grey background for odd items */
    }
    .striped-list li:nth-child(even) {
        background-color: #e9ecef; /* Slightly darker grey background for even items */
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
            let selectedItems = []; 
            document.querySelectorAll('.order-checkbox[data-order-id="' + orderId + '"]').forEach((checkbox) => { 
                if (checkbox.checked) { 
                    selectedItems.push(checkbox.value); 
                } 
            }); 

            dpBtn.innerText = selectedItems.length > 0 ? selectedItems.join(', ') : 'Select'; 
        } 
    });
</script> -->
                                                        </div>

                                                        <div class="row g-9 mb-8 text-start">
                                                            <div class="col-md-12 fv-row">
                                                                <label class="fs-6 fw-bold mb-2">Comment</label>
                                                                <textarea wire:model.defer="comment" required="" name="comment" value="" class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->qc_comment}}</textarea>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button wire:click.prevent="updateQc({{ $order->id }})" data-bs-dismiss="modal" type="submit" class="btn btn-primary">Save changes</button>                
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>                                    
                                @endforeach
                            </tbody>
                        </table>                        
                        <!-- Pagination -->
                        {{ $orders->links() }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
