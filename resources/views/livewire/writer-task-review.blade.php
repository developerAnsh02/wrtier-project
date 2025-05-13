<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Writer Task Review</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <input type="date" wire:model.live="selectedDate" class="form-control w-auto me-3">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($reports->isEmpty())
                <div class="alert alert-info">No tasks found for {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
            @else
                @foreach($reports->groupBy('user.name') as $writerName => $writerReports)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-user-edit me-2"></i>
                                {{ $writerName }} - {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Version</th>
                                            <th>Order Codes</th>
                                            <th>Total Words</th>
                                            <th>Status</th>
                                            <th>Submitted At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($writerReports as $report)
                                            <tr>
                                                <td>v{{ $report->version }}</td>
                                                <td>
                                                    @foreach(json_decode($report->tasks) as $task)
                                                        {{ $task->order_code }}<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($report->total_words) }}</td>
                                                <td>
                                                    @if($report->edit_request_status === 'pending')
                                                        <span class="badge bg-warning">Pending Approval</span>
                                                    @elseif($report->edit_request_status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($report->edit_request_status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @elseif($report->edit_request_status === 'archived')
                                                        <span class="badge bg-danger">Archived</span>
                                                    @else
                                                        @if($report->submitted_at)
                                                            <span class="badge bg-primary">Submitted</span>
                                                        @else
                                                            <span class="badge bg-warning">Not submitted</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M d, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    <button wire:click="showDetails({{ $report->id }})" class="btn btn-sm btn-info">
                                                            View
                                                    </button>
                                                    @if($report->edit_request_status === 'pending')                                                        
                                                        <button wire:click="show({{ $report->id }})" class="btn btn-sm btn-primary">
                                                            Approve / Reject
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- View Details Modal -->
    @if($showViewModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Task Report Details</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="resetModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($currentReport)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Writer Information</h6>
                            <p><strong>Name:</strong> {{ $currentReport->user->name ?? 'N/A' }}</p>
                            <p><strong>Task Date:</strong> {{ \Carbon\Carbon::parse($currentReport->task_date)->format('M d, Y') }}</p>
                            <p><strong>Version:</strong> v{{ $currentReport->version }}</p>
                            <p><strong>Reason:</strong> {{ $currentReport->edit_reason ?? 'No reason provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-bar me-2"></i>Summary</h6>
                            <p><strong>Total Words:</strong> {{ number_format($currentReport->total_words) }}</p>
                            <p><strong>Number of Tasks:</strong> {{ count(json_decode($currentReport->tasks)) }}</p>
                            <p><strong>Submitted At:</strong> 
                                @if($currentReport->submitted_at)
                                    {{ \Carbon\Carbon::parse($currentReport->submitted_at)->format('M d, Y h:i A') }}
                                @else
                                    <span class="badge bg-warning">N/A</span>
                                @endif
                            </p>
                            <p><strong>Status:</strong> 
                                @if($currentReport->edit_request_status === 'pending')
                                    <span class="badge bg-warning">Pending Approval</span>
                                @elseif($currentReport->edit_request_status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($currentReport->edit_request_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    @if($currentReport->submitted_at)
                                        <span class="badge bg-primary">Submitted</span>
                                    @else
                                        <span class="badge bg-warning">Not submitted</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6><i class="fas fa-tasks me-2"></i>Task Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Nature</th>
                                        <th>Word Count</th>
                                        <th>Comments</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(json_decode($currentReport->tasks) as $task)
                                    <tr>
                                        <td>{{ $task->order_code }}</td>
                                        <td>{{ $task->nature }}</td>
                                        <td>{{ number_format($task->word_count) }}</td>
                                        <td>{{ $task->comments }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->timestamp)->format('h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="alert alert-danger">Report data not loaded!</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetModal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Approval Modal -->
    @if($showApprovalModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Review Edit Request</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="resetModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($currentReport)
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle me-2"></i>Request Details</h6>
                            <p><strong>Writer:</strong> {{ $currentReport->user->name ?? 'N/A' }}</p>
                            <p><strong>Date:</strong> {{ $currentReport->task_date }}</p>
                            <p><strong>Reason:</strong> {{ $currentReport->edit_reason ?? 'No reason provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-tasks me-2"></i>Task Summary</h6>
                            <p><strong>Total Words:</strong> {{ number_format($currentReport->total_words) }}</p>
                            <p><strong>Tasks:</strong> {{ count(json_decode($currentReport->tasks)) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6><i class="fas fa-list-ol me-2"></i>Order Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Nature</th>
                                        <th>Words</th>
                                        <th>Comments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(json_decode($currentReport->tasks) as $task)
                                    <tr>
                                        <td>{{ $task->order_code }}</td>
                                        <td>{{ $task->nature }}</td>
                                        <td>{{ $task->word_count }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($task->comments, 30) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="alert alert-danger">Report data not loaded!</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" wire:click="rejectEditRequest" wire:loading.attr="disabled">
                        <i class="fas fa-times me-1"></i> 
                        <span wire:loading wire:target="rejectEditRequest">Processing...</span>
                        <span wire:loading.remove wire:target="rejectEditRequest">Reject</span>
                    </button>
                    <button type="button" class="btn btn-success" wire:click="approveEditRequest" wire:loading.attr="disabled">
                        <i class="fas fa-check me-1"></i> 
                        <span wire:loading wire:target="approveEditRequest">Processing...</span>
                        <span wire:loading.remove wire:target="approveEditRequest">Approve</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>