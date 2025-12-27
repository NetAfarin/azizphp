$(document).ready(function () {
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity,
    });
    $('#changeStatusSelect').select2({
        dropdownParent: $('#changeStatusModal'),
        width: '100%'
    });

    $('#changeStatusModal').on('shown.bs.modal', function () {
        $('#changeStatusSelect').select2({
            minimumResultsForSearch: Infinity,
            dropdownParent: $('#changeStatusModal'),
            width: '100%'
        });
    });
    $('#changeStatus').on('change', function () {
        var getStatus = $(this).val();
        var url = new URL(window.location.href);
        if (getStatus === 'all') {
            url.searchParams.delete('filter');
        } else {
            url.searchParams.set('filter', getStatus);
        }
        window.location.href = url.toString();
    });
    $('#changeStatusModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employeeId = button.data('employee_id');
        var registerDatetime = button.data('register_datetime');
        var id = button.data('id');
        var statusId = button.data('status');
        $('#employee_id').val(employeeId);
        $('#status_id').val(id);
        $('#register_datetime').val(registerDatetime);
        $('#changeStatusSelect').val(statusId).trigger('change');
    });

    $('#changeStatusForm')
        .off('submit')
        .on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData($(this).get(0));
            var serviceId = $('#status_id').val();
            var url = BASE_URL + '/operator/dashboard/changeStatus/' + serviceId;
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        console.log(data.success)
                        $('#changeStatusModal').modal('hide');
                        location.reload();
                    } else {
                        $('#changeStatusModal').modal('hide');
                        location.reload();
                    }
                }
            });
        });
});

