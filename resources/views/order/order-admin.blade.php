@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Order</h4>
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
                <h4 class="card-title ">Order Date</h4>
                <div class="table-responsive table-card">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SR NO</th>
                                <th>Order ID</th>
                                <th>Delivery From - Upto</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Words</th>
                                <th>Writer&TL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['order'] as  $key => $order)
                            <tr>
                                <td>{{ $key +1  }} </td>
                                <td>
                                    {{$order->order_id}}
                                </td>
                                <td>
                                    @if ($order->writer_fd && $order->writer_fd != '0000-00-00')
                                        <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_fd)->format('jS M ') }} <div style="color:red;  margin-left: 10px;"> {{ $order->writer_fd_h }}</div> </div> <br>
                                        <div class="d-flex">{{ \Carbon\Carbon::parse($order->writer_ud)->format('jS M ') }} <div style="color:red;  margin-left: 10px;">{{ $order->writer_ud_h }}</div></div>
                                    @else
                                        <div class="label label-table label-danger">Not Assigned</div>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->writer_deadline)->format('jS M Y') }}
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
                                <td>
                                    {{ $order->pages }}
                                </td>
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
                                                @if(order->subwriter && $order->subwriter->name)
                                                    {{$order->subwriter->name}}
                                                @endif
                                            </div>
                                    @endif
                                </td>
                               
                                <td class="text-nowrap" >
                                    <a data-bs-toggle="modal"class="model_img img-responsive" data-bs-target="#tooltipmodals{{$order->id}}"  data-bs-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                    <a href="javascript:void(0)" data-bs-toggle="tooltip" data-original-title="Close"> <i class="fa fa-eye text-danger"></i> </a>
                                    @include('order.component.edit-form')
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['order']->links() }}
                </div>
            </div>
        </div>
    </div>
    
    
</div>
@include('order.action.ajax_all_admin_order')

@endsection