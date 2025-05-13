<div class="container-fluid space-y-4">

    <!-- Page Header -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Admin Task Reports Overview</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="align-items-center" style="display: flex !important; justify-content: flex-end !important;">
                <input type="date" wire:model.live="selectedDate" class="form-control w-auto me-3">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Task Report Overview</li>
                </ol>
            </div>
        </div>
    </div>    

    <!-- TL Sections -->
    @foreach ($tls as $tl)
        <div class="card border border-light shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fas fa-user-tie me-2"></i>{{ $tl->name }}
            </div>
            <div class="card-body p-0">
                @foreach ($writersByTl[$tl->id] as $writer)
                    <div class="p-3 border-bottom">
                        <h5 class="fw-semibold text-dark">
                            <i class="fas fa-user-edit me-2"></i>{{ $writer->name }}
                        </h5>

                        @php $reports = $reportsByWriter[$writer->id] ?? collect(); @endphp

                        @if ($reports->isEmpty())
                            <p class="text-muted small fst-italic">No reports found.</p>
                        @else
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
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
                                        @foreach ($reports as $report)
                                            <tr>
                                                <td>v{{ $report->version }}</td>
                                                <td>
                                                    @foreach(json_decode($report->tasks, true) as $task)
                                                        <div>{{ $task['order_code'] }}</div>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($report->total_words) }}</td>
                                                <td>
                                                    @switch($report->edit_request_status)
                                                        @case('pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                            @break
                                                        @case('approved')
                                                            <span class="badge bg-success">Approved</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-info">
                                                                {{ $report->submitted_at ? 'Submitted' : 'Draft' }}
                                                            </span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M d, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    <button wire:click="showDetails({{ $report->id }})" class="btn btn-sm btn-info">View</button>
                                                    @if($report->edit_request_status === 'pending')
                                                        <button wire:click="show({{ $report->id }})" class="btn btn-sm btn-primary">Approve / Reject</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- View Modal --}}
    @if($showViewModal && $currentReport)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Task Report Details</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="resetModal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Writer Info</h6>
                            <p><strong>Name:</strong> {{ $currentReport->user->name ?? 'N/A' }}</p>
                            <!-- <p><strong>Date:</strong> {{ $currentReport->task_date }}</p> -->
                            <p>
                                <strong>Date:</strong> 
                                {{ (!empty($currentReport->task_date) && $currentReport->task_date !== '0000-00-00 00:00:00') 
                                    ? \Carbon\Carbon::parse($currentReport->task_date)->format('d M Y') 
                                    : 'N/A' 
                                }}
                            </p>
                            <p><strong>Version:</strong> v{{ $currentReport->version }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-bar me-2"></i>Submission Info</h6>
                            <p><strong>Total Words:</strong> {{ number_format($currentReport->total_words) }}</p>
                            <p>
                                <strong>Submitted At:</strong> 
                                {{ (!empty($currentReport->submitted_at) && $currentReport->submitted_at !== '0000-00-00 00:00:00') 
                                    ? \Carbon\Carbon::parse($currentReport->submitted_at)->format('d M Y h:i A') 
                                    : 'N/A' 
                                }}
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

                    <h6 class="fw-bold"><i class="fas fa-list-ol me-2"></i>Task Breakdown</h6>
                    @if(!empty($currentReport->tasks_array))
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
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
                                @foreach($currentReport->tasks_array as $task)
                                    <tr>
                                        <td>{{ $task['order_code'] ?? '-' }}</td>
                                        <td>{{ $task['nature'] ?? '-' }}</td>
                                        <td>{{ number_format($task['word_count'] ?? 0) }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($task['comments'] ?? '-', 40) }}</td>
                                        <td>
                                            @if(!empty($task['timestamp']) && $task['timestamp'] !== '0000-00-00 00:00:00')
                                                {{ \Carbon\Carbon::parse($task['timestamp'])->format('h:i A') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-muted">No task data available.</div>
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

    {{-- Approval Modal --}}
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
                            <!-- <p><strong>Date:</strong> {{ $currentReport->task_date }}</p> -->
                            <p>
                                <strong>Date:</strong> 
                                {{ (!empty($currentReport->task_date) && $currentReport->task_date !== '0000-00-00 00:00:00') 
                                    ? \Carbon\Carbon::parse($currentReport->task_date)->format('d M Y') 
                                    : 'N/A' 
                                }}
                            </p>
                            <p><strong>Reason:</strong> {{ $currentReport->edit_reason ?? 'No reason provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-tasks me-2"></i>Task Summary</h6>
                            <p><strong>Total Words:</strong> {{ number_format($currentReport->total_words) }}</p>
                            <p><strong>Tasks:</strong> {{ is_array($currentReport->tasks_array) ? count($currentReport->tasks_array) : 0 }}</p>
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
                                    @foreach($currentReport->tasks_array ?? [] as $task)
                                    <tr>
                                        <td>{{ $task['order_code'] ?? '-' }}</td>
                                        <td>{{ $task['nature'] ?? '-' }}</td>
                                        <td>{{ $task['word_count'] ?? '-' }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($task['comments'] ?? '-', 30) }}</td>
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
