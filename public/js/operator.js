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

});
    var changeStatusModal = document.getElementById('changeStatusModal');
    changeStatusModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    document.getElementById('status_id').value = id;
});
    document.getElementById('changeStatusForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    var id = document.getElementById('status_id').value;
    var selectedValue = $('#changeStatusSelect').val();
    if (selectedValue != 'all'){
        fetch(`${BASE_URL}/operator/dashboard/changeStatus/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                $('#changeStatusModal').modal('hide');
                location.reload();
            } else {
                alert(data.message || 'خطا در بروزرسانی سرویس');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در بروزرسانی سرویس');
        });}

});
    var allVisits = document.getElementById("allVisits");
    allVisits.addEventListener("change", function () {
    var table = this.closest("table");
    var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
    checkboxes.forEach(cb => cb.checked = allVisits.checked);
});

