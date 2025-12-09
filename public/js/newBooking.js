$(document).ready(function () {
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity,
    });
    const modal = $('#finalReserve');
    if (modal.data('show-modal')) {
        modal.modal('show');
        console.log("ساخص ئخیشم")
    }
    let lang = getLangFromURL();
    $('.search-block').each(function () {
        const $form = $(this);

        $form.find('.service_select').change(function () {
            const serviceId = $(this).val();
            const $getService = $form.find('.getService');
            $getService.text($(this).find('option:selected').text());

            if (!serviceId) return;

            $.ajax({
                url: `${BASE_URL}/admin/bookings/get/${serviceId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const $employeeSelect = $form.find('.employee_select');
                    const $employeeDate = $form.find('.employee_date');
                    const $employeeTime = $form.find('.employee_time');

                    $employeeSelect.empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.first_name,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');

                    $employeeDate.empty();
                    $employeeTime.empty();

                    // بارگذاری دیفالت تاریخ و زمان کارمند اول
                    const firstEmployeeId = $employeeSelect.val();
                    if (firstEmployeeId) {
                        $form.find('.employee_select').trigger('change');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });
        });

        $form.find('.employee_select').change(function () {
            const employeeId = $(this).val();
            const serviceId = $form.find('.service_select').val();
            const $getEmployee = $form.find('.getEmployee');
            $getEmployee.text($(this).find('option:selected').text());

            $.ajax({
                url: `${BASE_URL}/admin/bookings/getEmployeeDate/${employeeId}/${serviceId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const $employeeDate = $form.find('.employee_date');
                    $employeeDate.empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.date,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });
        });

        $form.find('.employee_date').change(function () {
            const dateId = $(this).val();
            const $employeeTime = $form.find('.employee_time');
            const $getEmployeeDate = $form.find('.getEmployeeDate');

            $.ajax({
                url: `${BASE_URL}/admin/bookings/getEmployeeTime/${dateId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    $employeeTime.empty();
                    (response.data || []).forEach((item) => {
                        const optionText = item.status == 1 ? item.time + ' (رزرو شده)' : item.time;
                        const $option = $('<option>', {
                            value: item.id,
                            text: optionText,
                            'data-status': item.status
                        });
                        if (item.status == 1) $option.prop('disabled', true);
                        $employeeTime.append($option);
                    });

                    updateEmployeeDateDisplay();
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });

            function updateEmployeeDateDisplay() {
                const time = $employeeTime.find('option:selected').text();
                const date = $form.find('.employee_date option:selected').text();
                if (time && date) {
                    $getEmployeeDate.text(date + (lang === "en" ? " / Hour " : " / ساعت ") + time.match(/\d{1,2}:\d{2}/));
                }
            }

            $employeeTime.add($form.find('.employee_date')).off('change.update').on('change.update', updateEmployeeDateDisplay);
        });

        $form.find('.btn-search-btn').click(function () {
            const $icon = $(this).find('i');
            const mobile = $form.find('.search-input').val();
            const $phone = $form.find('.phone_number');
            const $firstName = $form.find('.first_name');
            const $firstNameHidden = $form.find('.first_name_hidden');
            const $lastName = $form.find('.last_name');
            const $lastNameHidden = $form.find('.last_name_hidden');
            const $fullName = $form.find('.fullName');

            if (!mobile || !/^\d{11}$/.test(mobile)) {
                showBootstrapAlert('شماره موبایل وارد شده صحیح نمیباشد.');
                return;
            }

            if ($icon.hasClass('fa-xmark')) {
                $form.find('.search-input').val('').prop('disabled', false);
                $phone.val('').prop('disabled', false);
                $firstName.val('').prop('disabled', false);
                $lastName.val('').prop('disabled', false);
                $fullName.text('');
                $icon.removeClass('fa-xmark').addClass('fa-search');
                return;
            }

            $icon.removeClass('fa-search').addClass('fa-xmark');

            $.ajax({
                url: `${BASE_URL}/admin/bookings/searchUser/${mobile}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.data.length > 0) {
                        $firstName.val(data.data[0].first_name).prop('disabled', true);
                        $firstNameHidden.val(data.data[0].first_name);
                        $lastName.val(data.data[0].last_name).prop('disabled', true);
                        $lastNameHidden.val(data.data[0].last_name);
                        $fullName.text(data.data[0].first_name + " " + data.data[0].last_name);
                        $phone.val(mobile).prop('disabled', true);
                    } else {
                        $('#notFoundModal').data('mobile', mobile).modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ Error:', error);
                    $icon.removeClass('fa-xmark').addClass('fa-search');
                }
            });
        });
        const $serviceSelect = $form.find('.service_select');
        if (!$serviceSelect.val()) {
            $serviceSelect.val($serviceSelect.find('option').first().val());
        }
        $serviceSelect.trigger('change');
    });
    $('.block-with-time_priority').each(function () {
        const $form = $(this);

        $form.find('.service_select').change(function () {
            const serviceId = $(this).val();
            const $getService = $form.find('.getService');
            $getService.text($(this).find('option:selected').text());

            if (!serviceId) return;

            $.ajax({
                url: `${BASE_URL}/admin/bookings/get/${serviceId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const $employeeSelect = $form.find('.employee_select');
                    const $employeeDate = $form.find('.employee_date');
                    const $employeeTime = $form.find('.employee_time');

                    $employeeSelect.empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.first_name,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');

                    $employeeDate.empty();
                    $employeeTime.empty();
                    const firstEmployeeId = $employeeSelect.val();
                    if (firstEmployeeId) {
                        $form.find('.employee_select').trigger('change');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });
        });

        $form.find('.employee_select').change(function () {
            const employeeId = $(this).val();
            const serviceId = $form.find('.service_select').val();
            const $getEmployee = $form.find('.getEmployee');
            $getEmployee.text($(this).find('option:selected').text());

            $.ajax({
                url: `${BASE_URL}/admin/bookings/getEmployeeDate/${employeeId}/${serviceId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const $employeeDate = $form.find('.employee_date');
                    $employeeDate.empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.date,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });
        });

        $form.find('.employee_date').change(function () {
            const dateId = $(this).val();
            const $employeeTime = $form.find('.employee_time');
            const $getEmployeeDate = $form.find('.getEmployeeDate');

            $.ajax({
                url: `${BASE_URL}/admin/bookings/getEmployeeTime/${dateId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    $employeeTime.empty();
                    (response.data || []).forEach((item) => {
                        const optionText = item.status == 1 ? item.time + ' (رزرو شده)' : item.time;
                        const $option = $('<option>', {
                            value: item.id,
                            text: optionText,
                            'data-status': item.status
                        });
                        if (item.status == 1) $option.prop('disabled', true);
                        $employeeTime.append($option);
                    });

                    updateEmployeeDateDisplay();
                },
                error: function (xhr, status, error) {
                    console.error('❌ خطا در دریافت پاسخ:', status, error);
                }
            });

            function updateEmployeeDateDisplay() {
                const time = $employeeTime.find('option:selected').text();
                const date = $form.find('.employee_date option:selected').text();
                if (time && date) {
                    $getEmployeeDate.text(date + (lang === "en" ? " / Hour " : " / ساعت ") + time.match(/\d{1,2}:\d{2}/));
                }
            }

            $employeeTime.add($form.find('.employee_date')).off('change.update').on('change.update', updateEmployeeDateDisplay);
        });

        $form.find('.btn-search-btn').click(function () {
            const $icon = $(this).find('i');
            const mobile = $form.find('.search-input').val();
            const $phone = $form.find('.phone_number');
            const $firstName = $form.find('.first_name');
            const $firstNameHidden = $form.find('.first_name_hidden');
            const $lastName = $form.find('.last_name');
            const $lastNameHidden = $form.find('.last_name_hidden');
            const $fullName = $form.find('.fullName');

            if (!mobile || !/^\d{11}$/.test(mobile)) {
                showBootstrapAlert('شماره موبایل وارد شده صحیح نمیباشد.');
                return;
            }

            if ($icon.hasClass('fa-xmark')) {
                $form.find('.search-input').val('').prop('disabled', false);
                $phone.val('').prop('disabled', false);
                $firstName.val('').prop('disabled', false);
                $lastName.val('').prop('disabled', false);
                $fullName.text('');
                $icon.removeClass('fa-xmark').addClass('fa-search');
                return;
            }

            $icon.removeClass('fa-search').addClass('fa-xmark');

            $.ajax({
                url: `${BASE_URL}/admin/bookings/searchUser/${mobile}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.data.length > 0) {
                        $firstName.val(data.data[0].first_name).prop('disabled', true);
                        $firstNameHidden.val(data.data[0].first_name);
                        $lastName.val(data.data[0].last_name).prop('disabled', true);
                        $lastNameHidden.val(data.data[0].last_name);
                        $fullName.text(data.data[0].first_name + " " + data.data[0].last_name);
                        $phone.val(mobile).prop('disabled', true);
                    } else {
                        $('#notFoundModal').data('mobile', mobile).modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ Error:', error);
                    $icon.removeClass('fa-xmark').addClass('fa-search');
                }
            });
        });

        const $serviceSelect = $form.find('.service_select');
        if (!$serviceSelect.val()) {
            $serviceSelect.val($serviceSelect.find('option').first().val());
        }
        $serviceSelect.trigger('change');
    });



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