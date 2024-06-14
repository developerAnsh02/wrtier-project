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
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="card-title ">Order Date</h4> -->
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
                                            @if($order->qc_checked == 1 )
                                                <div>
                                                    <input onchange="toggleCheck(this, {{ $order->id }})" type="checkbox" checked value="1">
                                                </div>
                                            @else
                                                <div>
                                                    <input onchange="toggleCheck(this, {{ $order->id }})" type="checkbox"  value="1">
                                                </div>
                                            @endif

                                            <!-- <script>
                                                function toggleCheck(checkbox, leadId) {
                                                    // Toggle checkbox state
                                                    checkbox.checked ? uncheckLead(leadId) : checkLead(leadId);
                                                }

                                                function uncheckLead(leadId) {
                                                    $.ajax({
                                                        url: 'qcchecked/' + leadId,
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function(response) {
                                                            console.log("Lead with ID " + leadId + " unchecked successfully.");
                                                            // Reload the page
                                                            location.reload();
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error("Error: " + error);
                                                        }
                                                    });
                                                }
                                            </script> -->
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
                                    <td id="ai-score" class="editable " data-order-id="{{ $order->id }}">
                                        @if($order->ai_score != null)
                                            {{ $order->ai_score }} %
                                        @else
                                            <div class="label label-table label-danger"></div>
                                        @endif
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
                                        @if($order->subwriter && Auth::user()->role_id==1)                                                                                        
                                            <div class="label label-table label-danger">
                                                Old Sub Writer :  {{ $order->subwriter->name }}
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
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="editQc({{$order->id}})" class="btn btn-sm btn-light-primary"><li class="fa fa-edit"> </li></button>
                                    </td>
                                    @push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('editModal', () => {
            $('#editModal').modal('show'); // Show the modal when 'editModal' event is emitted
        });

        $('#editModal').on('hide.bs.modal', () => {
            Livewire.emit('editModalClosed'); // Emit an event when the modal is closed
        });
    });
</script>
@endpush
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                        
                        <!-- Pagination -->
                        {{ $orders->links() }}
                    </div>
                    <!-- Modal -->
<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit QC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add your form or content for editing here -->
                <p>Edit QC content here...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- Add a Save changes button if needed -->
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>


                </div>
            </div>
        </div>
    </div>
</div>
