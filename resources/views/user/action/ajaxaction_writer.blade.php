<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Include SweetAlert library -->



<script>
    $(document).on("change", '#writer-tl', function(e) {
        e.preventDefault();
        let tlid = $('#writer-tl').val();

        $.ajax({
            url: "{{ route('search.writer') }}",
            method: 'GET',
            data: { tlid: tlid },

            success: function(data) {
                $('.table-data').html(data); // Update the content of .table-data
            },

            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
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

    $(document).on('click', '.add_writer', function(e) {
        e.preventDefault();
        $('.errmsgContainer').empty();
        let name = $('#name').val();
        let email = $('#email').val();
        let tl = $('#writer-tl').val();

        $.ajax({
            url: '{{ route('add.writer') }}',
            method: 'POST',
            data: {
                name: name,
                email: email,
                tl: tl
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#writerdata')[0].reset();
                    $('.table').load(location.href + ' .table');
                    toastr.success("TL Inserted Successfully", "Success", {
                        closeButton: true,
                        debug: false,
                        newestOnTop: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    });
                } else {
                    toastr.warning("Not Able To Insert Data", "Warning", {
                        closeButton: true,
                        debug: false,
                        newestOnTop: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    });
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(index, value) {
                    $('.errmsgContainer').append(`<span class="text-danger">${value}</span><br>`);
                    toastr.warning(value, "Warning", {
                        closeButton: true,
                        debug: false,
                        newestOnTop: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    });
                });
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

        $(document).on('click', '.update_writer', function(e) {
            e.preventDefault();
            
            $('.errmsgContainer').html('');
            
            let id = $('#writer_id').val();
            let name = $('#name').val();
            let email = $('#email').val();
            let tl_id = $('#writer-tl').val();

            if (!id) {
                alert('ID is missing.');
                return;
            }

            $.ajax({
                url: `/writer/${id}`,
                method: 'PUT',
                data: {
                    name: name,
                    email: email,
                    tl_id: tl_id
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('.table').load(location.href + ' .table');
                        toastr.success("TL Updated Successfully", "Success", {
                            closeButton: true,
                            debug: false,
                            newestOnTop: false,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            preventDuplicates: false,
                            showDuration: 300,
                            hideDuration: 1000,
                            timeOut: 5000,
                            extendedTimeOut: 1000,
                            showEasing: "swing",
                            hideEasing: "linear",
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut"
                        });
                    } else {
                        toastr.warning("Not Able To Update Data", "Warning", {
                            closeButton: true,
                            debug: false,
                            newestOnTop: false,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            preventDuplicates: false,
                            showDuration: 300,
                            hideDuration: 1000,
                            timeOut: 5000,
                            extendedTimeOut: 1000,
                            showEasing: "swing",
                            hideEasing: "linear",
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut"
                        });
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(index, value) {
                        $('.errmsgContainer').append(`<span class="text-danger">${value}</span><br>`);
                        toastr.warning(value, "Warning", {
                            closeButton: true,
                            debug: false,
                            newestOnTop: false,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            preventDuplicates: false,
                            showDuration: 300,
                            hideDuration: 1000,
                            timeOut: 5000,
                            extendedTimeOut: 1000,
                            showEasing: "swing",
                            hideEasing: "linear",
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut"
                        });
                    });
                }
            });
        });
    });
</script>

<script>
    function deactivate(id) {
        swal({
            title: "Are you sure?",
            text: "Click 'OK' to deactivate this Writer Team Leader",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: '/writer/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("TL Delete Succesfully", "Success")

                            toastr.options = {
                            "closeButton": true,
                            "debug": true,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            }

                        } else {
                            alert('Update failed. Please try again.');

                        }
                    },
                    error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(index, value) {
                    $('.errmsgContainer').append(`<span class="text-danger">${value}</span><br>`);
                    toastr.warning(value, "Warning", {
                        closeButton: true,
                        debug: false,
                        newestOnTop: false,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    });
                });
            }
                });
            } else {
                swal("Deletion cancelled!", { icon: "info" });
            }
        });
    }
</script>


<script>
    function editDatawriter(id, event) {
        event.preventDefault();

        $.ajax({
            url: '/writer.' + id,
            type: 'GET',
            success: function(data) {
                console.log(data);
                $('.writer-card').html(data);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error response here, maybe show an alert
            }
        });
    }
</script>


