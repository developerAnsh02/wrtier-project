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
                                    <th>Order ID</th>
                                    <th>Title</th>
                                    <th>Writer</th>
                                    <th>SubWriter</th>
                                    <th>Admin</th>
                                    <th>QC Standard</th>
                                    <th>Status</th>
                                    <th>Writer Deadline</th>
                                    <th>QC Date</th>
                                    <!-- Add other columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->title }}</td>
                                        <td>{{ $order->writer->name ?? '' }}</td>
                                        <td>{{ $order->subwriter->name ?? '' }}</td>
                                        <td>{{ $order->qc_admin }}</td>
                                        <td>{{ $order->qc_standard }}</td>
                                        <td>{{ $order->writer_status }}</td>
                                        <td>{{ $order->writer_deadline }}</td>
                                        <td>{{ $order->qc_date }}</td>
                                    </tr>
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
