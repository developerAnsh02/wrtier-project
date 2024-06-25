<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Ticket Generated Order</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">T G O</li>
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
                    <form>
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <input wire:model.debounce.500ms="orderCode" type="input" name="order" id="ordercode"
                                    class="form-control form-control-solid form-select-lg" placeholder="Order Code">
                            </div>
                            <div class="col-md-3 fv-row">
                                <input wire:model.debounce.500ms="fromDate" type="date" name="fromDate" id="fromDate"
                                    class="form-control form-control-solid form-select-lg" placeholder="From Date">
                            </div>
                            <div class="col-md-3 fv-row">
                                <input wire:model.debounce.500ms="toDate" type="date" name="toDate" id="toDate"
                                    class="form-control form-control-solid form-select-lg" placeholder="To Date">
                            </div>
                            <div class="col-md-3 fv-row">
                                <select wire:model.debounce.500ms="status" name="status" id="status"
                                    class="form-control form-control-solid form-select-lg">
                                    <option value="">Select Status</option>
                                    <option value="Issue Raised">Issue Raised</option>
                                    <option value="Client Discussion Done">Client Discussion Done</option>
                                    <option value="Writer discussion Done">Writer discussion Done</option>
                                    <option value="Work in progress">Work in progress</option>
                                    <option value="Case Resolved">Case Resolved</option>
                                    <option value="Issues Raised Again">Issues Raised Again</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <button type="button" wire:click="$refresh"
                                    class="btn btn-sm btn-primary">Search</button>
                                <button type="button" wire:click="resetFilters"
                                    class="btn btn-sm btn-danger">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body position-relative">
                    <div class="table-responsive table-card">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Order Code</th>
                                    <th>TL/Writer</th>
                                    <th>Feedback Date</th>
                                    <th>Status</th>
                                    <th>Ticket Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->index + 1 }}</td>
                                        <td>
                                            <b>{{$order->order_id}}</b>
                                            <br>
                                            @if($order->tech == 'on')
                                                <div class="label label-table label-success">Technical</div>
                                            @endif
                                            @if($order->is_fail == 1)
                                                <div class="label label-table label-danger">Failed</div>
                                            @endif
                                            @if($order->resit == 1)
                                                <div class="label label-table label-danger">Resit</div>
                                            @endif

                                        </td>
                                        <td>
                                            @if($order->wid)
                                                @if($order->writer && $order->writer->name)
                                                    {{ $order->writer->name }} <br>
                                                @endif
                                            @else
                                                <div class="label label-table label-danger">Not Assigned</div>
                                            @endif

                                            @if($order->mulsubwriter)
                                                @foreach($order->mulsubwriter as $writer)
                                                    @if($writer->user && $writer->user->name)
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
                                        <td>{{ \Carbon\Carbon::parse($order->feedback_date)->format('d F Y H:i') }}</td>
                                        <td>
                                            @if($order->status_issue == 'Issue Raised')
                                                <span class="label label-table label-danger">{{$order->status_issue}}</span>
                                            @elseif($order->status_issue == 'Client Discussion Done')
                                                <span class="label label-table label-warning">{{$order->status_issue}}</span>
                                            @elseif($order->status_issue == "Writer discussion Done")
                                                <span class="label label-table label-success">{{$order->status_issue}}</span>
                                            @elseif($order->status_issue == 'Work in progress')
                                                <span class="label label-table label-info">{{$order->status_issue}}</span>
                                            @elseif($order->status_issue == 'Case Resolved')
                                                <span class="label label-table label-success">{{$order->status_issue}}</span>
                                            @elseif($order->status_issue == 'Issues Raised Again')
                                                <span class="label label-table label-danger">{{$order->status_issue}}</span>
                                            @else
                                                <span class="label label-table label-primary">{{$order->status_issue}}</span>

                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                                data-bs-target="#commentsModal{{$order->id}}">
                                                View Comments
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for Comments -->
    @foreach ($orders as $order)
        <div class="modal fade" id="commentsModal{{$order->id}}" tabindex="-1"
            aria-labelledby="commentsModalLabel{{$order->id}}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentsModalLabel{{$order->id}}">Comments for Order
                            {{$order->order_id}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->feedback as $feedback)
                                    @if($feedback->comment != '')
                                        <tr>
                                            <td>Marketing Team </td>
                                            <td>{{ $feedback->comment }}</td>
                                            <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('d F Y') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>