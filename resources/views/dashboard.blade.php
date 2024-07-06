@extends('layouts.app')

@section('content')
<style>
        .container {
            height: auto;
        }
        .hidden {
            display: none;
        }
    </style>
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Dashboard </h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
    @if(Auth::user()->role_id == 6 || Auth::user()->role_id == 7 || Auth::user()->role_id == 8)
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round align-self-center round-success"><i style="font-size:48px;" class="fa fa-first-order"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0"> 
                                    @if(Auth::user()->role_id == 8)
                                        {{$data['TotalOrders']}}
                                    @elseif(Auth::user()->role_id == 7)
                                        {{$data['TotalOrdersWriter']}}
                                    @elseif(Auth::user()->role_id == 6)
                                        {{$data['TotalOrdersTl']}}
                                    @endif
                                </h3>
                                <h5 class="text-muted m-b-0">Total Order</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round align-self-center round-danger"><i style="font-size:48px;" class="fa fa-clock-o"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0">
                                    @if(Auth::user()->role_id == 8)
                                        {{$data['InprogressOrder']}}
                                    @elseif(Auth::user()->role_id == 7)
                                        {{$data['InprogressOrderWriter']}}
                                    @elseif(Auth::user()->role_id == 6)
                                        {{$data['InprogressOrderTl']}}
                                    @endif    
                                </h3>
                                <h5 class="text-muted m-b-0">In progress Order</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round align-self-center round-info"><i style="font-size:48px;" class="fa fa-ban"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0">
                                    @if(Auth::user()->role_id == 8)
                                        {{$data['NotAssignOrder']}}
                                    @elseif(Auth::user()->role_id == 7)
                                        {{$data['NotAssignOrderWriter']}}
                                    @elseif(Auth::user()->role_id == 6)
                                        {{$data['NotAssignOrderTl']}}
                                    @endif 
                                </h3>
                                <h5 class="text-muted m-b-0">Not Assign Order</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-lg-{{ Auth::user()->role_id == 7 ? '12' : '4' }} col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title ">Order Anylysis</h5>
                        <div id="morris-donut-chart" class="ecomm-donute" style="height: 317px;"></div>
                        <ul class="list-inline m-t-30 text-center">
                            <li class="p-r-20">
                                <h5 class="text-muted"><i class="fa fa-circle" style="color: #fb9678;"></i> Ads</h5>
                                <h4 class="m-b-0">8500</h4>
                            </li>
                            <li class="p-r-20">
                                <h5 class="text-muted"><i class="fa fa-circle" style="color: #01c0c8;"></i> Tredshow</h5>
                                <h4 class="m-b-0">3630</h4>
                            </li>
                            <li>
                                <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4F5467;"></i> Web</h5>
                                <h4 class="m-b-0">4870</h4>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if(Auth::user()->role_id == 8)
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My All Team Leader And Writer </h5>
                            <div class="container">
                            <nav class="sidebar-nav">
                                <ul id="sidebarnav">
                                @foreach($data['Tl'] as $tl)
                                    
                                        <li>
                                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                                <i class="fa fa-user"></i>
                                                <span class="hide-menu">{{$tl->name}} (TL)</span>
                                            </a>
                                            @if($tl->writer->isNotEmpty() )
                                                <ul aria-expanded="false" class="collapse">
                                                    @foreach($tl->writer as $writer)
                                                        <li><a >{{$writer->name}} (Writer   )</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        

                                @endforeach
                                </ul>
                            </nav>
                            {{ $data['Tl']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->role_id == 6)
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">All My Writer </h5>
                            <div class="container">
                            <nav class="sidebar-nav">
                                <ul id="sidebarnav">
                                @foreach($data['Tl'] as $tl)
                                    
                                        @if($tl->id == Auth::user()->id && $tl->writer->isNotEmpty() )
                                            @foreach($tl->writer as $writer)
                                                <li><a ><i class="fa fa-user"></i> {{$writer->name}} (Writer   )</a></li>
                                            @endforeach
                                        @endif
                                        

                                @endforeach
                                </ul>
                            </nav>
                            
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif(Auth::user()->role_id == 3 || Auth::user()->role_id == 5)
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title py-2 card-header">Team Member</h4>
                        @foreach($data['TeamMemberData'] as $teamMember)
                            <div class="d-flex align-items-center m-2">
                                <div>
                                    <!-- <img src="{{ $teamMember->photo ? asset('storage/' . $teamMember->photo) : asset('assets/media/avatars/blank.png') }}" class="" alt="" style="height: 50px; width: auto; border-radius: 15%; object-fit: cover; border: 2px solid #999999; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);" /> -->
                                    <img src="{{ asset('assets/media/avatars/blank.png') }}" class="" alt="" style="height: 50px; width: auto; border-radius: 15%; object-fit: cover; border: 2px solid #999999; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);" />
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="text-dark fw-bolder fs-4 px-3">{{ $teamMember->name }}</a>
                                </div>
                            </div>
                        @endforeach                        
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<p id="displayText"></p>

<script>
    function showText(writer) {
        const displayText = document.getElementById("displayText");
        const writerList = document.getElementById("writerList");
        const listItems = writerList.getElementsByTagName("li");

        for (let i = 0; i < listItems.length; i++) {
            if (listItems[i].id === writer) {
                listItems[i].classList.remove("hidden");
            } else {
                listItems[i].classList.add("hidden");
            }
        }

        if (writer === "ravi") {
            displayText.innerText = "buswrtri";
        } else {
            displayText.innerText = "dub write rt";
        }
    }
</script>
@endsection