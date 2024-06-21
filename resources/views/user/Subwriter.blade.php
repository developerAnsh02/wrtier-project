@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">All Writer's </h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Writer</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card writer-card">
                <div class="card-body">
                    <h4 class="card-title">Insert New Writer </h4>
                    <form class="form p-t-20" action="" method="post" id="writerdata">
                        <div class="errmsgContainer"></div>

                        <div class="form-group">
                            <label for="name">User Name</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon1" id="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2"><i class="ti-email"></i></span>
                                <input type="text" value="" class="form-control" placeholder="Email" aria-label="Email"
                                    aria-describedby="basic-addon2" id="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="writer-tl" class="form-label">Team Leader</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                <select id="writer-tl" class="form-control form-select">
                                    <option value="">Select TL</option>
                                    @foreach($data['tl'] as $tl)
                                        <option value="{{ $tl->id }}">
                                            {{ $tl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-success waves-effect waves-light m-r-10 text-white add_writer">Submit</button>
                            <button type="reset" class="btn btn-inverse waves-effect waves-light">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All Writer List</h4>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-data">
                                <div class="table-responsive ">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Writer Name/Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['writer'] as $writet)
                                                <tr>
                                                    <td>{{$writet->name}} <br>
                                                        <div class="parimary">{{$writet->email}} </div>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <a onclick="editDatawriter({{ $writet->id }}, event)"
                                                            data-bs-toggle="tooltip" data-original-title="Edit">
                                                            <i class="fa fa-pencil text-inverse m-r-10"></i>
                                                        </a>
                                                        <a class="delete-btn" onclick="deactivate({{$writet->id}})"
                                                            data-bs-toggle="tooltip" data-original-title="Close">
                                                            <i class="fa fa-close text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.action.ajaxaction_writer')
@endsection