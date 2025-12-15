$(document).ready(function () {
    let lang = $('html').attr('lang');
    $('.userRoleSelect').select2({
        minimumResultsForSearch: Infinity
    });

    $('.userBreakTimeSelect').select2({
        minimumResultsForSearch: Infinity
    });

    $('#multiple-select-field').select2({
        minimumResultsForSearch: Infinity, dropdownParent: $(document.body)
    });
    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });
    const holidayCheckBox = $('.holiday-checkbox');

    holidayCheckBox.each(function () {
        const index = $(this).data('id');
        if ($(this).is(':checked')) {
            $('#startTime-' + index).prop('disabled', true);
            $('#endTime-' + index).prop('disabled', true);
        }
    });

    holidayCheckBox.on('change', function () {
        const index = $(this).data('id');
        const checked = $(this).is(':checked');
        $('#startTime-' + index).prop('disabled', checked);
        $('#endTime-' + index).prop('disabled', checked);
    });

    $('#multiple-select-field').on('change', function () {
        const selectedOptions = $(this).select2('data');
        const $wrapper = $('#services_table_wrapper');

        if (selectedOptions.length === 0) {
            $wrapper.html('');
            return;
        }
        let tableHTML = `
            <div class="table-wrapper">
                <table class="table transparent custom-table">
                    <thead>
                        <tr>
                            <th>${lang === 'fa' ? "عنوان خدمت" : "Service Title"}</th>
                            <th>${lang === 'fa' ? "قیمت (تومان)" : "Price (Toman)"}</th>
                            <th>${lang === 'fa' ? "مدت زمان" : "Duration"}</th>
                            <th>${lang === 'fa' ? "حذف" : "Delete"}</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        selectedOptions.forEach(opt => {
            let durationOptionsHTML = '';
            durations.forEach(d => {
                durationOptionsHTML += `
                    <option value="${d.id}">
                        ${lang === 'fa' ? d.title : d.en_title}
                    </option>`;
            });

            tableHTML += `
                <tr data-service-id="${opt.id}">
                    <td>${opt.text}</td>
                    <td>
                        <input type="text" min="0" class="form-control" name="service_prices[]" required>
                    </td>
                    <td>
                        <select name="service_durations[]" class="js-duration-select" required>
                            ${durationOptionsHTML}
                        </select>
                    </td>
                    <td>
                        <i class="fa fa-close remove-row icon-color" style="cursor:pointer;"></i>
                    </td>
                </tr>
            `;
        });

        tableHTML += `
                    </tbody>
                </table>
            </div>
        `;

        $wrapper.html(tableHTML);

        $('.js-duration-select').select2({
            placeholder: "انتخاب مدت زمان", width: '100%', minimumResultsForSearch: Infinity
        });
    });

    $(document).on('click', '.remove-row', function () {
        const $row = $(this).closest('tr');
        const serviceId = $row.data('service-id');
        $row.remove();

        const $select = $('#multiple-select-field');
        let selectedValues = $select.val() || [];
        selectedValues = selectedValues.filter(id => id !== String(serviceId));
        $select.val(selectedValues).trigger('change');
    });

    var switchData = $('#switchBox')[0];
    var table = $('#workTable')[0];
    var shiftTitle = $('#shiftTitle')[0];
    var section = $('#employeeSection')[0];
    var role = $('#userRole');

    function updateTableDisplay() {
        var optionId = role.find('option:selected').val();

        if (optionId == "1") {
            switchBox.style.display = "block";
            shiftTitle.style.display = "block";
            section.style.display = "block";
            table.style.display = switchData.checked ? "none" : "block";
        } else {
            switchBox.style.display = "none";
            table.style.display = "none";
            shiftTitle.style.display = "none";
            section.style.display = "none";
        }
    }

    switchData.addEventListener('change', updateTableDisplay);
    role.on('change', updateTableDisplay);
    updateTableDisplay();
});
