<!-- order live pagiation  -->

<script>
    $(document).on("click", '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        writePage(page);
    });

    function writePage(page) {
        $.ajax({
            url: "/order/paginate-data?page=" + page,
            success: function(data) {
                $('.table-card').html(data); // Update the content of .card-body
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

<script>
    $(document).on("click", '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        writePage(page);
    });

    function getForm(OrderId) {
        $.ajax({
            url: "/order-form/" + OrderId,
            success: function(data) {
                $('.edit-form').html(data); // Update the content of .edit-form
                $('html, body').animate({
                    scrollTop: $(".edit-form").offset().top
                }, 1000); // Scroll to the .edit-form
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

    function editDate(OrderId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.preventDefault();  // Prevent the default form submission
    $('.errmsgContainer').html('');  // Clear any previous error messages

    let fromdate = $('#from_date' + OrderId).val();
    let fromdate_time = $('#from_date_time' + OrderId).val();
    let uptodate = $('#upto_date' + OrderId).val();
    let uptodate_time = $('#upto_date_time' + OrderId).val();

    $.ajax({
        url: "/order/" + OrderId,
        method: 'PUT',
        data: {
            fromdate: fromdate,
            fromdate_time: fromdate_time,
            uptodate: uptodate,
            uptodate_time: uptodate_time
        },
        success: function(data) {
            $('.table').load(location.href + ' .table');            let row = $('#order-row-' + OrderId);
          
            toastr["success"]("TL Updated Successfully", "Success");
            $('#tooltipmodals' + OrderId).modal('hide');  // Hide the modal
        },
        error: function(xhr, status, error) {
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

    return false; // Prevent the default form submission
}


</script>
