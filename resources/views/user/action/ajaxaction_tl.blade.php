
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- Include SweetAlert library -->


<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.add_product', function(e) {
            e.preventDefault();
            $('.errmsgContainer').html('');
            let name = $('#name').val();
            let email = $('#email').val();

            $.ajax({
                url: '{{ route('add.tl') }}',  
                method: 'POST',
                data: {
                    name: name,
                    email: email
                },
                success: function(response) {
                    if(response.status == 'success') {
                        $('#tlDatasubmit')[0].reset();
                        $('.table').load(location.href + ' .table');
                        Command: toastr["success"]("TL Inserted Succesfully", "Success")
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
                        alert('Submission failed. Please try again.');
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


<!-- UPDATE DATA -->
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

            if (!id) {
                alert('ID is missing.');
                return;
            }

            $.ajax({
                url: `/writerTL/${id}`,
                method: 'PUT',
                data: {
                    name: name,
                    email: email
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('.table').load(location.href + ' .table');
                        toastr["success"]("TL Updated Successfully", "Success");
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
                    url: '/writerTL/' + id,
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

<!-- live pagination -->
<script>
    $(document).on("click", '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        writePage(page);
    });

    function writePage(page) {
        $.ajax({
            url: "/pagination/paginate-data?page=" + page,
            success: function(data) {
                $('.table-data').html(data); // Update the content of .card-body
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
    }
</script>


<!-- Edit Page Without Reload  -->
<script>
    function editData(id, event) {
        event.preventDefault();

        $.ajax({
            url: '/writerTL/' + id,
            type: 'GET',
            success: function(data) {
                console.log(data);
                $('.tl-card').html(data);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error response here, maybe show an alert
            }
        });
    }
</script>






