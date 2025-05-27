<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Writer Task Review</h4>
        </div>
        <div class="col-md-7 text-end">
            <input type="date" wire:model.live="selectedDate" class="form-control w-auto d-inline-block">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($reports->isEmpty())
                <div class="alert alert-info">
                    No tasks found for {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                </div>
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
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Order Code</th>
                                            <th>Nature</th>
                                            <th>Word Count</th>
                                            <th>Comments</th>
                                            <th>Time</th>
                                            <th>Total Words</th>
                                            <th>Status</th>
                                            <th>Submitted At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp
                                        @foreach($writerReports as $report)
                                            @foreach(json_decode($report->tasks) as $task)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $task->order_code ?? '-' }}</td>
                                                    <td>{{ $task->nature ?? '-' }}</td>
                                                    <td>{{ number_format($task->word_count ?? 0) }}</td>
                                                    <td>{{ $task->comments ?? '-' }}</td>
                                                    <td>{{ isset($task->timestamp) ? \Carbon\Carbon::parse($task->timestamp)->format('h:i A') : '-' }}</td>
                                                    <td>{{ number_format($report->total_words) }}</td>
                                                    <td>
                                                        @php $status = $report->edit_request_status; @endphp
                                                        <span class="badge
                                                            @if($report->submitted_at) bg-primary
                                                            @else bg-warning
                                                            @endif
                                                        ">
                                                            @if($report->submitted_at)
                                                                Submitted
                                                            @else
                                                                Not Submitted
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>{{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M d, h:i A') : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
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
</div>
