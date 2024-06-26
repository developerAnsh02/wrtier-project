<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fs-5 mb-1">Edit Data Of {{ $data['orderdata']->order_id }}</span>
        </h3>
    </div>
    <div class="card-body py-3 ">
        <form onsubmit="return editDate({{ $data['orderdata']->id }})">
            <div class="row mb-2">
                <div class="col-md-3 fv-row">
                    <label for="writer_fd" class="form-label">Writer FD</label>
                    <input type="date" value="{{ $data['orderdata']->writer_fd }}" id="from_date{{ $data['orderdata']->id }}" name="writer_fd" class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="writer_ud" class="form-label">Writer UD</label>
                    <input type="date" value="{{ $data['orderdata']->writer_ud }}" id="upto_date{{ $data['orderdata']->id }}" name="writer_ud" class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="writer_fd_h" class="form-label">Writer FD Hour</label>
                    <input type="time" value="{{ $data['orderdata']->writer_fd_h }}" id="from_date_time{{ $data['orderdata']->id }}" name="writer_fd_h" class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="writer_ud_h" class="form-label">Writer UD Hour</label>
                    <input type="time" value="{{ $data['orderdata']->writer_ud_h }}" id="upto_date_time{{ $data['orderdata']->id }}" name="writer_ud_h" class="form-control form-control-solid form-select-lg">
                </div>
                <div class="col-md-3 fv-row">
                    <label for="writer_ud_h" class="form-label">Select Tl</label>
                    <select class="form-control form-select mt-3" >
                        <option value="">Select TL</option>
                            @foreach($data['tl'] as $tl)
                                <option value="{{ $tl->id }}" >{{ $tl->name }}</option>
                            @endforeach
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
