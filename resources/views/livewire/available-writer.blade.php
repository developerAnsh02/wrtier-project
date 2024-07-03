<div class="container-fluid">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Available Writer</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">A-W</li>
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
                    <form wire:submit.prevent="applyFilters">
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <input wire:model="filterFromDate" type="date" name="fromDate" id="fromDate"
                                    class="form-control form-control-solid form-select-lg" placeholder="From Date">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 fv-row">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
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
                                    <th>Available Date</th>
                                    <th>Writer Name</th>
                                    <th>Timer After Free</th>
                                    <th>Word Count Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $writer)
                                    <tr>
                                        <td class="text-center">
                                            {{$loop->index + 1}}
                                        </td>
                                        <td>{{$filterDate}}</td>
                                        <td style="white-space: nowrap;">
                                            {{$writer['name']}}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            Full Day
                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{$writer['wordcount']}}
                                        </td>
                                    </tr>
                                @endforeach
                                @php $startingIndex = count($users) + 1; @endphp
                                @foreach($usersWithTime as $writer)
                                    <tr>
                                        <td class="text-center">
                                            {{$startingIndex + $loop->index}}
                                        </td>
                                        <td>{{$filterDate}}</td>
                                        <td style="white-space: nowrap;">
                                            {{$writer['name']}}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{$writer['writerWork'][0]['order']['writer_ud_h']}}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @php
                                                $officeStartTime = \Carbon\Carbon::createFromTime(9, 0, 0);
                                                $officeEndTime = \Carbon\Carbon::createFromTime(18, 0, 0);
                                                $writerOrderTime = \Carbon\Carbon::createFromFormat('H:i', $writer['writerWork'][0]['order']['writer_ud_h']);
                                                $totalMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                                                $workingMinutes = $writerOrderTime->diffInMinutes($officeStartTime);
                                                $remainingMinutes = $totalMinutes - $workingMinutes;
                                            @endphp
                                            
                                            {{ number_format($writer['wordcount' ] /  $totalMinutes * $remainingMinutes , 2) }}<br>
                                          
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
