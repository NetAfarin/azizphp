$(document).ready(function () {
    $("#birth_date_picker").persianDatepicker({
        format: 'YYYY-MM-DD',
        autoClose: true,
        initialValue: false,
        altField: '#birth_date',
        altFormat: 'YYYY-MM-DD',
        calendar: {
            persian: {
                locale: 'fa'
            },
            gregorian: {
                locale: 'en'
            }
        }
    });

    const oldBirthDate = $("#birth_date_picker").data("old");
    if (oldBirthDate) {
        $('#birth_date').val(oldBirthDate);
        $('#birth_date_picker').val(oldBirthDate);
    }
    const durationOptions = window.durations.map(d =>
        `<option value="${d.id}">${d.title}</option>`
    ).join('');

    const serviceSelect = $('.js-example-basic-multiple');
    const servicesTable           = $('#services_table');
    serviceSelect.select2();
    serviceSelect.on('select2:select', function (e) {
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
            servicesTable.find('tbody').append(row);
        }
    });

    serviceSelect.on('select2:unselect', function (e) {
        const serviceId = e.params.data.id;
        $('#row-' + serviceId).remove();
    });

    $(document).on('click', '.remove-row', function () {
        const serviceId = $(this).data('id');

        let values = $serviceSelect.val() || [];
        values = values.filter(id => id != serviceId);
        serviceSelect.val(values).trigger('change');

        $('#row-' + serviceId).remove();

    });
});
