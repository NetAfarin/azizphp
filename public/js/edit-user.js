$(document).ready(function () {
    const durationOptions = window.durations.map(d =>
        `<option value="${d.id}">${d.title}</option>`
    ).join('');
    const $userTypeSelect          = $('#user_type_select');
    const $employeeServicesSection = $('#employee_services_section');
    const $servicesTableWrapper    = $('#services_table_wrapper');
    const $servicesTable           = $('#services_table');
    const $serviceSelect           = $('.js-example-basic-multiple');


    function toggleEmployeeServices() {
        const selectedText = $userTypeSelect.find('option:selected').text().toLowerCase();

        if (selectedText.includes('کارمند') || selectedText.includes('employee')) {

            $employeeServicesSection.show();
            $servicesTableWrapper.show();
            $serviceSelect.select2();
        } else {
            $employeeServicesSection.hide();
            $servicesTableWrapper.hide();

            $servicesTable.find('tbody').empty();

            $serviceSelect.val(null).trigger('change');
        }
    }

    toggleEmployeeServices();

    $userTypeSelect.on('change', toggleEmployeeServices);

    $serviceSelect.select2();

    $serviceSelect.on('select2:select', function (e) {
        $servicesTableWrapper.show();

        const serviceId   = e.params.data.id;
        const serviceText = e.params.data.text;

        if ($('#row-' + serviceId).length === 0) {
            const row = `
                <tr id="row-${serviceId}">
                    <td>${serviceText}</td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               class="form-control"
                               name="service_prices[${serviceId}]"
                               required>
                    </td>
                    <td>
                        <select name="service_durations[${serviceId}]" class="form-select" required>
                            ${durationOptions}
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${serviceId}">✖</button>
                    </td>
                </tr>`;
            $servicesTable.find('tbody').append(row);
        }
    });

    $serviceSelect.on('select2:unselect', function (e) {
        const serviceId = e.params.data.id;
        $('#row-' + serviceId).remove();

        if ($servicesTable.find('tbody').children().length === 0) {
            $servicesTableWrapper.hide();
        }
    });

    $(document).on('click', '.remove-row', function () {
        const serviceId = $(this).data('id');

        let values = $serviceSelect.val() || [];
        values = values.filter(id => id != serviceId);
        $serviceSelect.val(values).trigger('change');

        $('#row-' + serviceId).remove();

        if ($servicesTable.find('tbody').children().length === 0) {
            $servicesTableWrapper.hide();
        }
    });
});