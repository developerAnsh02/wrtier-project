<div class="card-body">
    <h4 class="card-title">Edit Writer TL</h4>
        <div class="errmsgContainer">
        </div>
        <div class="form-group">
            <label for="exampleInputuname">User Name</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                <input value="@if(isset($data['WriterTl'])) {{$data['WriterTl']->name}} @endif" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" id="name">
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon2"><i class="ti-email"></i></span>
                <input type="text" value="@if(isset($data['WriterTl'])) {{$data['WriterTl']->email}} @endif" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" id="email">
            </div>
        </div>
            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 text-white update_writer">Submit</button>
            <input type="hidden" value="{{$data['WriterTl']->id}}" id="writer_id">
            <a href="{{ route('writerTL') }}" class="btn btn-warning waves-effect waves-light">Insert New</a>
           
    </form>
</div>