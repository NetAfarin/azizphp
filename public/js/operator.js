$(document).ready(function() {
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
    url.searchParams.delete('status');
} else {
    url.searchParams.set('status', getStatus);
}
    window.location.href = url.toString();
});
    $('#changeStatusModal').on('show.bs.modal', function(event) {
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

    $('#changeStatusForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData($(this).get(0));
        var serviceId = $('#status_id').val();
        var selectedValue = $('#changeStatusSelect').val();
        if (selectedValue !== 'all') {
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
                success: function(data) {
                    if (data.success) {
                        console.log(data)
                        $('#changeStatusModal').modal('hide');
                        location.reload();
                    } else {
                        alert(data.message || 'خطا در بروزرسانی سرویس');
                    }
                }
            });
        } else {
            alert('لطفاً وضعیت را انتخاب کنید');
        }
    });
});
    var changeStatusModal = document.getElementById('changeStatusModal');
    changeStatusModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    document.getElementById('status_id').value = id;
});
$('#changeStatusForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    var id = $('#status_id').val();
    var selectedValue = $('#changeStatusSelect').val();

    if (selectedValue !== 'all') {
        $.ajax({
            url: BASE_URL + '/operator/dashboard/changeStatus/' + id,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    console.log(data);
                    // $('#changeStatusModal').modal('hide');
                    // location.reload();
                } else {
                    alert(data.message || 'خطا در بروزرسانی سرویس');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('خطا در بروزرسانی سرویس');
            }
        });
    }
});    var allVisits = document.getElementById("allVisits");
    allVisits.addEventListener("change", function () {
    var table = this.closest("table");
    var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
    checkboxes.forEach(cb => cb.checked = allVisits.checked);
});

