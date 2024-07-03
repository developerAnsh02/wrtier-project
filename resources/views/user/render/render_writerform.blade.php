<div class="card-body">
    <h4 class="card-title">Edit Writer</h4>
   
    <form class="form p-t-20" action="" method="post" id="writerdata">
        <div class="errmsgContainer"></div>
        
        <div class="form-group">
            <label for="name">User Name</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                <input type="text" class="form-control" placeholder="Username" value="@if($data['editablewriter']) {{$data['editablewriter']->name}} @endif" aria-label="Username" aria-describedby="basic-addon1" id="name">
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon2"><i class="ti-email"></i></span>
                <input type="text" value="@if(isset($data['editablewriter'])) {{$data['editablewriter']->email}} @endif" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" id="email">
            </div>
        </div>
        <div class="form-group">
            <label for="name">Word Count Capacity</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-address-card"></i></span>
                <input type="text" class="form-control" placeholder="Word Count" aria-label="Username"
                    aria-describedby="basic-addon1" id="wordcount">
            </div>
        </div>

        <div class="form-group">
            <label for="writer-tl" class="form-label">Team Leader</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                <select id="writer-tl" class="form-control form-select">
                    <option value="">Select TL</option>
                    @foreach($data['tl'] as $tl)
                        <option value="{{ $tl->id }}" 
                            @if(isset($data['editablewriter']) && $data['editablewriter']->tl_id == $tl->id) 
                                selected 
                            @endif>
                            {{ $tl->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 text-white update_writer">Submit</button>
            <a href="/subwriter" class="btn btn-warning waves-effect waves-light">Insert New Writer</a>
            <input type="hidden" value="{{$data['editablewriter']->id}}" id="writer_id">
        </div>
    </form>
</div>