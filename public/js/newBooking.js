$(document).ready(function (){
    let lang = getLangFromURL();
    // getDetails(["first_name", "last_name"], "fullName");

    $('#service_id').change(function() {
        let text = $("#service_id option:selected").text();
        $("#getService").text(text);
        var serviceId = $(this).val();
        if (!serviceId) {
            console.log('سرویس انتخاب نشده');
            return;
        }
        var url = `${BASE_URL}/admin/bookings/get/` + serviceId;
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#employee_time, #employee_date').empty();
                $('#employee_select').empty().append(
                    (response.data || []).map((item, index) =>
                        $('<option>', {
                            value: item.id,
                            text: item.first_name,
                            selected: index === 0,
                        })
                    )
                ).trigger('change');
            },
            error: function(xhr, status, error) {
                console.log('❌ خطا در دریافت پاسخ:');
                console.log('   وضعیت:', status);
                console.log('   خطا:', error);
                console.log('   پاسخ سرور:', xhr.responseText);
                console.log('   کد وضعیت:', xhr.status);
            }
        });
    });
    $('#employee_select').change(function (){
        let serviceId = $("#service_id").val();
        var employeeId = $(this).val();
        var url = `${BASE_URL}/admin/bookings/getEmployeeDate/${employeeId}/${serviceId}`;
        let text = $("#employee_select option:selected").text();
        $("#getEmployee").text(text);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#employee_date').empty().append(
                    (response.data || []).map((item, index) =>
                        $('<option>', {
                            value: item.id,
                            text: item.date,
                            selected: index === 0,
                        })
                    )
                ).trigger('change');
            },

            error: function(xhr, status, error) {
                console.log('❌ خطا در دریافت پاسخ:');
                console.log('   وضعیت:', status);
                console.log('   خطا:', error);
                console.log('   پاسخ سرور:', xhr.responseText);
                console.log('   کد وضعیت:', xhr.status);
            }
        });
    });
    const finalReserveModal = document.getElementById('finalReserve');

    if (finalReserveModal && finalReserveModal.dataset.showModal === 'true') {
        $('#finalReserve').modal('show');
    }
    $('#employee_date').change(function (){
        var dateId = $(this).val();
        var url = `${BASE_URL}/admin/bookings/getEmployeeTime/` + dateId;
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response.data)
                const dateSelect = $('#employee_time');
                console.log(dateSelect)
                dateSelect.empty();
                (response.data || []).forEach((item, index) => {
                    const optionText = item.status == 1 ? item.time + ' (رزرو شده)' : item.time;

                    const option2 = $('<option>', {
                        value: item.id,
                        text: optionText ,
                        'data-status': item.status

                    });
                    if (item.status == 1) {
                        option2.prop('disabled', true);
                    }
                    dateSelect.append(option2);
                })
                let time = $("#employee_time option:selected").text();
                let date = $("#employee_date option:selected").text();

                if (time !== "" && date !== "") {
                    $("#getEmployeeDate").text(
                        date + ((lang === "en") ? " / Hour " : " / ساعت ") + time.match(/\d{1,2}:\d{2}/)
                    );
                }
                $("#employee_time, #employee_date").on('change', function() {
                    let time = $("#employee_time option:selected").text();
                    let date = $("#employee_date option:selected").text();

                    if (time !== "" && date !== "") {
                        $("#getEmployeeDate").text(
                            date + ((lang === "en") ? " / Hour " : " / ساعت ") + time.match(/\d{1,2}:\d{2}/)
                        );
                    }
                });

            },
            error: function(xhr, status, error) {
                console.log('❌ خطا در دریافت پاسخ:');
                console.log('   وضعیت:', status);
                console.log('   خطا:', error);
                console.log('   پاسخ سرور:', xhr.responseText);
                console.log('   کد وضعیت:', xhr.status);
            }
        });

    });
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity,


    });

    $('#service_id').trigger('change');
    $('#btn-search').on('click', function() {
        var $icon = $(this).find('i');
        var mobile = $('#search').val();
        if (!mobile || !/^\d{11}$/.test(mobile)) {
            showBootstrapAlert('شماره موبایل وارد شده صحیح نمیباشد.');
            return;
        }
        if ($icon.hasClass('fa-xmark')) {
            $('#search').val('').prop('disabled', false);
            $('#phone_number').val('').prop('disabled', false);
            $('#first_name').val('').prop('disabled', false);
            $('#last_name').val('').prop('disabled', false);
            $('#fullName').text(''); // ← درست
            $icon.removeClass('fa-xmark').addClass('fa-search');
            return;
        }
        $icon.removeClass('fa-search').addClass('fa-xmark');
        $.ajax({
            url: `${BASE_URL}/admin/bookings/searchUser/${mobile}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data)
                if (data.data.length > 0) {
                    $('#first_name').val(data.data[0].first_name).prop('disabled', true);
                    $('#first_name_hidden').val(data.data[0].first_name);
                    $('#last_name').val(data.data[0].last_name).prop('disabled', true);
                    $('#last_name_hidden').val(data.data[0].last_name);
                    $('#fullName').text(data.data[0].first_name + " " + data.data[0].last_name);
                    $('#phone_number').val(mobile).prop('disabled', true);
                } else {
                    $('#notFoundModal').data('mobile', mobile);
                    $('#notFoundModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error:', error);
                $icon.removeClass('fa-xmark').addClass('fa-search');
            }
        });
    });

});
$('#notFoundModal').on('hidden.bs.modal', function() {
    $('#search').val('');
    $('#btn-search').find('i').removeClass('fa-xmark').addClass('fa-search');
});
$('#notFoundModal').on('click', '.show-modal', function() {
    var mobile = $('#notFoundModal').data('mobile');
    $('#notFoundModal').modal('hide');
    $('#phone_number_modal').val(mobile);
    $('#createUserModal').modal('show');

});
$('#createUserModal').on('shown.bs.modal', function() {
    $('#phone_number_modal').prop('disabled', true);
});
$('#createUserModal').on('click', '.btn-modal', function() {
    var phone = $('#phone_number_modal').val();
    var firstName = $('#first_name_modal').val();
    var lastName = $('#last_name_modal').val();
    $('#phone_number_hidden').val(phone);
    $('#phone_number').val(phone).prop('disabled', true);
    $('#first_name').val(firstName).prop('disabled', true);
    $('#last_name').val(lastName).prop('disabled', true);
    $('#fullName').text(firstName + " " + lastName);


    $('#createUserModal').modal('hide');
});

function switchTab(index) {
    $('#circle0, #circle1').removeClass('active');
    $('#circle' + index).addClass('active');
    $('#content0').css('display', index === 0 ? 'block' : 'none');
    $('#content1').css('display', index === 1 ? 'block' : 'none');
}
switchTab(0);
function showBootstrapAlert(message) {
    var alertHtml = `
        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#exclamation-triangle-fill"/>
            </svg>
            <div>${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('#alertContainer').html(alertHtml);
    setTimeout(function() {
        $('.alert').alert('close');
    }, 2000);
}
function getDetails(getValues, result) {

    if (!Array.isArray(getValues)) {
        getValues = [getValues];
    }

    function updateResult() {
        let combined = "";

        getValues.forEach(function(id) {
            combined += $(`#${id}`).val() + " ";
        });

        $(`#${result}`).text(combined.trim());
    }
    getValues.forEach(function(id) {
        $(`#${id}`).on("change keyup", updateResult);
    });
}
function getLangFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('lang') || urlParams.get('language') || 'fa';
}