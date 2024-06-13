@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">All Writer's  </h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active"> TL</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card tl-card">
                <div class="card-body">
                    <h4 class="card-title">Insert New Writer TL </h4>
                    <form class="form p-t-20" action="" method="post" id="tlDatasubmit">
                        <div class="errmsgContainer">

                        </div>
                        <div class="form-group">
                            <label for="exampleInputuname">User Name</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                <input value="" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2"><i class="ti-email"></i></span>
                                <input type="text" value="" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" id="email">
                            </div>
                        </div>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 text-white add_product">Submit</button>
                            <button type="reset" class="btn btn-inverse waves-effect waves-light">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All TL List</h4>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-data">
                                <div class="table-responsive ">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Writer TL</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['tl'] as $tl)
                                            <tr>
                                                <td>{{$tl->name}} <br>
                                                <div class="parimary">{{$tl->email}}</div>
                                                </td>
                                                <td class="text-nowrap">
                                                <a onclick="editData({{ $tl->id }}, event)" data-bs-toggle="tooltip" data-original-title="Edit">
                                                    <i class="fa fa-pencil text-inverse m-r-10"></i>
                                                </a>
                                                <a class="delete-btn" onclick="deactivate({{ $tl->id }})" data-bs-toggle="tooltip" data-original-title="Close">
                                                    <i class="fa fa-close text-danger"></i>
                                                </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $data['tl']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@include('user.action.ajaxaction_tl')


@endsection