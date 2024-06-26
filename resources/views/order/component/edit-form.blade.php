<div id="tooltipmodals{{$order->id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="tooltipmodel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tooltipmodel">Order Edit {{$order->id}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row d-flex align-items-center">
                    <div class="col-md-12">
                        <div class="form-group has-success">
                            <select class="form-control form-select mt-3" id="writer-tl{{$order->id}}">
                                <option value="">Select TL</option>
                                    @foreach($data['tl'] as $tl)
                                        <option value="{{ $tl->id }}" {{ $order->wid == $tl->id ? 'selected' : '' }}>{{ $tl->name }}</option>
                                    @endforeach
                            </select>

                        </div>
                    </div>
                </div>
                <div class="row d-flex align-items-center">
                    <div class="col-md-12">
                        <div class="form-group has-success writer{{$order->id}}">
                            <select name="countries[]" id="writer{{$order->id}}" multiple>
                                @foreach($data['writer'] as $writer)
                                    @php
                                        $user_ids = $order->multiple->user_id ?? [];
                                    @endphp
                                    @if($writer->admin_id == $order->wid)
                                        <option value="{{ $writer->id }}" {{ in_array($writer->id, $user_ids) ? 'selected' : '' }}>
                                            {{ $writer->name }}
                                        </option>
                                    @else
                                        <option value="{{ $writer->id }}">
                                            {{ $writer->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" value="{{$order->id}}" class="id{{$order->id}}">

                <div class="row d-flex align-items-center">
                    <div class="col-md-12">
                        <div class="form-group has-success" >
                            <select class="form-control form-select mt-3" id="status{{$order->id}}">
                                <option value="">Not Assigned</option>
                                <option value="Completed">Completed</option>
                                <option value="Inprogress">Inprogress</option>
                                <option value="dfd">jdf</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="date" class="form-control" id="from_date{{$order->id}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="time" class="form-control" id="from_date_time{{$order->id}}">
                        </div>
                    </div>
                </div>

                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="date" class="form-control" id="upto_date{{$order->id}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="time" class="form-control" id="upto_date_time{{$order->id}}">
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="modal-footer">
            <button type="submit" class="btn btn-success waves-effect text-white update_order{{$order->id}}">Update</button>
            <button type="button" class="btn btn-info waves-effect text-white" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>


<script>
    new MultiSelectTag('writer{{$order->id}}')  
</script>


<script>
    $(document).ready(function() {
        $(document).on("change", '#writer-tl{{$order->id}}', function(e) {
            e.preventDefault();
            let tlid = $(this).val(); 
            let id = $('.id{{$order->id}}').val();
            $.ajax({
                url: "{{ route('search.writer-order') }}",
                method: 'GET',
                data: { tlid: tlid ,
                        id :id
                },

                success: function(data) {
                    $('.writer{{$order->id}}').html(data); 
                },

                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.update_order{{$order->id}}').click(function(e) {
            e.preventDefault();
            $('.errmsgContainer').html('');
          
            let selectedWriters = [];
                $('#writer{{$order->id}} option:selected').each(function() {
                    selectedWriters.push($(this).val());
                });

            let id             = $('.id{{$order->id}}').val();
            let tlid           = $('#writer-tl{{$order->id}}').val();
            let status         = $('#status{{$order->id}}').val();
            let fromdate       = $('#from_date{{$order->id}}').val();
            let fromdate_time  = $('#from_date_time{{$order->id}}').val();
            let uptodate       = $('#upto_date{{$order->id}}').val();
            let uptodate_time  = $('#upto_date_time{{$order->id}}').val();
            
            
            $.ajax({
                url: "/order/" + id,
                method: 'PUT',
                data: 
                { 
                    id            : id,
                    tlid          : tlid ,
                    status        : status,
                    fromdate      : fromdate,
                    fromdate_time : fromdate_time,
                    fromdate_time : fromdate_time,
                    uptodate      : uptodate,
                    uptodate_time : uptodate_time
                },

                success: function(data) {
                    $('.table').load(location.href + ' .table');
                    toastr["success"]("TL Updated Successfully", "Success");
                    $('#tooltipmodals{{$order->id}}').modal('hide'); 
                },

                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

        });
    });
</script>