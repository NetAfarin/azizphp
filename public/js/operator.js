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
    url.searchParams.delete('filter');
} else {
    url.searchParams.set('filter', getStatus);
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

    $('#changeStatusForm')
        .off('submit')
        .on('submit', function(e) {
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
                        $('#changeStatusModal').modal('hide');
                        location.reload();
                    }
                }
            });
            //todo nemidonam chejori handle konam
        } else {
            alert('لطفاً وضعیت را انتخاب کنید');
        }
    });

    // var btnSearch = $('#btn-search');
    // var btnSearchIcon = $('#btn-search i');
    // if (btnSearch.length > 0) {
    //     btnSearch.on('click', function(e) {
    //         console.log("کلیک شد");
    //     });
    // } else {
    //     $('#search').on('input', function() {
    //         var searchValue = $(this).val();
    //         var processedValue = processSearchValue(searchValue);
    //         var btn = $('#btn-delete, #btn-search');
    //
    //         if(processedValue.length == 0) {
    //             console.log("empty");
    //             btn.find('i').removeClass('fa-xmark').addClass('fa-search');
    //             btn.attr('id', 'btn-search').attr('type', 'submit');
    //         } else {
    //             console.log("has text");
    //             btn.find('i').removeClass('fa-xmark').addClass('fa-search');
    //             btn.attr('id', 'btn-delete').attr('type', 'submit');
    //             // $("#btn-delete").click(function(){
    //             //     searchValue = "";
    //             // });
    //         }
    //     });
    // }

    // $(document).on('click', '#btn-delete', function(e){
    //     e.preventDefault(); // جلوگیری از رفتار پیش‌فرض
    //
    //     // پاک کردن مقدار فیلد جستجو
    //     $('#search').val('');
    //
    //     // تغییر آیکون به search
    //     $('#btn-delete').find('i').removeClass('fa-xmark').addClass('fa-search');
    //
    //     // تغییر id و type دکمه
    //     $('#btn-delete').attr('id', 'btn-search').attr('type', 'submit');
    //
    //     // پاک کردن پارامتر سرچ از URL و رفرش صفحه
    //     var url = new URL(window.location.href);
    //     url.searchParams.delete('search'); // یا هر پارامتری که برای جستجو استفاده می‌کنید
    //     window.location.href = url.toString();
    // });


    // $('#btn-search').on('click', function() {
    //     var btnSearchIcon = $('#btn-search i');
    //
    //     // بررسی می‌کنیم که آیا آیکون × نمایش داده می‌شود یا خیر
    //     if(btnSearchIcon.hasClass('fa-xmark')) {
    //         $('#search').val(''); // پاک کردن مقدار فیلد جستجو
    //         $('#search').trigger('input'); // فعال کردن event input برای به‌روزرسانی آیکون
    //         $('#search').focus(); // نگه داشتن فوکوس روی فیلد جستجو
    //     }
    // });


});

function processSearchValue(value) {
    return value;
}