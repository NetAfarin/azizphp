$(document).ready(function () {
    $('.perPageSelect').select2({
        minimumResultsForSearch: Infinity
    });
    $('.groupWorkSelect').select2({
        minimumResultsForSearch: Infinity
    });
    $('#allUsers').on('change', function() {
        var checked = $(this).prop('checked');
        $(this).closest('table').find('tbody input[type="checkbox"]').prop('checked', checked);
    });

    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';

    $('#filterLinks a').each(function () {
        const link = $(this);
        link.removeClass('text-primary').addClass('text-dark');

        const href = new URL(link.attr('href'), location.origin);

        if (href.searchParams.get('filter') === filter) {
            link.removeClass('text-dark').addClass('text-primary');
        }
    });

    $('#editModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const userId = button.data('id');
        const userType = button.data('userType');

        $.ajax({
            url: `${BASE_URL}/admin/user/getUserData/${userId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#user_id').val(data.id || '');
                $('#first_name').val(data.first_name || '');
                $('#last_name').val(data.last_name || '');
                $('#phone_number').val(data.phone_number || '');
                $('#user_role').val(data.role_id || '').trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userId = $('#user_id').val();

        $.ajax({
            url: `${BASE_URL}/admin/user/update/${userId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                console.log('User data:', data);
                if (data.success) {
                    alert(data.message);
                    $('#editModal').modal('hide');
                    location.reload();
                } else {
                    alert(data.message || 'خطا در بروزرسانی سرویس');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating service:', error);
                alert('خطا در بروزرسانی سرویس');
            }
        });
    });

    $('#itemsInPage').on('change', function() {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });
});