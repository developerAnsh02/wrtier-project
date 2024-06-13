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
