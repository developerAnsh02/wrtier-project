<div class="table-responsive">
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