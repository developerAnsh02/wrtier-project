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
                <a onclick="editDatawriter({{ $writet->id }}, event)"data-bs-toggle="tooltip" data-original-title="Edit">
                    <i class="fa fa-pencil text-inverse m-r-10"></i>
                </a>
                <a class="delete-btn" onclick="deactivate()" data-bs-toggle="tooltip" data-original-title="Close">
                    <i class="fa fa-close text-danger"></i>
                </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>