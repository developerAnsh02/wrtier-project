<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fs-5 mb-1">Edit Data Of {{ $data['orderdata']->order_id }}</span>
        </h3>
    </div>
    <div class="card-body py-3">
        <form onsubmit="return editDate({{ $data['orderdata']->id }})">
            <div class="row mb-2">
                <div class="col-md-3 fv-row">
                    <label for="from_date{{ $data['orderdata']->id }}" class="form-label">Writer FD</label>
                    <input type="date" value="{{ $data['orderdata']->writer_fd }}"
                        id="from_date{{ $data['orderdata']->id }}" name="writer_fd"
                        class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="upto_date{{ $data['orderdata']->id }}" class="form-label">Writer UD</label>
                    <input type="date" value="{{ $data['orderdata']->writer_ud }}"
                        id="upto_date{{ $data['orderdata']->id }}" name="writer_ud"
                        class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="from_date_time{{ $data['orderdata']->id }}" class="form-label">Writer FD Hour</label>
                    <input type="time" value="{{ $data['orderdata']->writer_fd_h }}"
                        id="from_date_time{{ $data['orderdata']->id }}" name="writer_fd_h"
                        class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="upto_date_time{{ $data['orderdata']->id }}" class="form-label">Writer UD Hour</label>
                    <input type="time" value="{{ $data['orderdata']->writer_ud_h }}"
                        id="upto_date_time{{ $data['orderdata']->id }}" name="writer_ud_h"
                        class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="tl_select{{ $data['orderdata']->id }}" class="form-label">Select TL</label>
                    <select id="tl_select{{ $data['orderdata']->id }}" name="tl_select"
                        class="form-control form-select">
                        <option value="">Select TL</option>
                        @foreach($data['tl'] as $tl)
                            <option value="{{ $tl->id }}" {{ $data['orderdata']->wid == $tl->id ? 'selected' : '' }}>
                                {{ $tl->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-3 fv-row">
                    <label for="writer{{ $data['orderdata']->id }}" class="form-label">Select Writer</label>
                        <select name="writers[]" id="writer{{ $data['orderdata']->id }}" multiple
                            class="form-control form-select">
                            <option value="">Select Writer</option>
                            @foreach($data['writer'] as $writer)
                                <option value="{{ $writer->id }}" {{ $data['orderdata']->mulsubwriter->pluck('user_id')->contains($writer->id) ? 'selected' : '' }}>{{ $writer->name }}</option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-3 fv-row">
                    <label for="status{{ $data['orderdata']->id }}" class="form-label">Status</label>
                    <select id="status{{ $data['orderdata']->id }}" name="status" class="form-control form-select">
                        <option {{ $data['orderdata']->writer_status == '' ? 'selected' : '' }} value="">Select Status
                        </option>
                        <option {{ $data['orderdata']->writer_status == 'In Progress' ? 'selected' : '' }}
                            value="In progress">In Progress</option>
                        <option {{ $data['orderdata']->writer_status == 'Completed' ? 'selected' : '' }}
                            value="Completed">Completed</option>
                        <option {{ $data['orderdata']->writer_status == 'Delivered' ? 'selected' : '' }}
                            value="Delivered">Delivered</option>
                        <option {{ $data['orderdata']->writer_status == 'Hold' ? 'selected' : '' }} value="Hold">Hold
                        </option>
                        <option {{ $data['orderdata']->writer_status == 'Draft Delivered' ? 'selected' : '' }}
                            value="Draft Delivered">Draft Delivered</option>
                        <option {{ $data['orderdata']->writer_status == 'Feedback' ? 'selected' : '' }} value="Feedback">
                            Feedback</option>
                        <option {{ $data['orderdata']->writer_status == 'Feedback Delivered' ? 'selected' : '' }}
                            value="Feedback Delivered">Feedback Delivered</option>
                        <option {{ $data['orderdata']->writer_status == 'Quality Accepted' ? 'selected' : '' }}
                            value="Quality Accepted">Quality Accepted</option>
                        <option {{ $data['orderdata']->writer_status == 'Quality Rejected' ? 'selected' : '' }}
                            value="Quality Rejected">Quality Rejected</option>
                    </select>
                </div>

            </div>
            <div class="row mb-2">
                <div class="col-md-3 fv-row">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    new MultiSelectTag('writer{{ $data['orderdata']->id }}')  
</script>


<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>