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

$(document).ready(function () {
    $('#multiple-select-field').select2()
})
$(document).ready(function() {
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const dropdownLinks = document.querySelectorAll('.nav-item.dropdown > a.dropdown-toggle[href^="#"]');

    dropdownLinks.forEach(link => {
        const collapseId = link.getAttribute('href');
        const collapseEl = document.querySelector(collapseId);
        if (!collapseEl) return;
        if (collapseEl.classList.contains('show')) {
            link.classList.add('right-border');
            link.classList.add('active-menu');
            link.setAttribute('aria-expanded', 'true');
        }
        collapseEl.addEventListener('show.bs.collapse', function () {
            dropdownLinks.forEach(l => l.classList.remove('right-border'));
            link.classList.add('right-border');
            link.classList.add('active-menu');
            link.setAttribute('aria-expanded', 'true');
        })
        collapseEl.addEventListener('hide.bs.collapse', function () {
            link.classList.remove('right-border');
            link.classList.remove('active-menu');
            link.setAttribute('aria-expanded', 'false');
        });
    });
});
var selectAllUsers = document.getElementById("allUsers");
var selectAllServices = document.getElementById("allServices");
selectAllUsers.addEventListener("change", function () {
    var table = this.closest("table");
    var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
    checkboxes.forEach(cb => cb.checked = selectAllUsers.checked);
});
selectAllServices.addEventListener("change", function () {
    var table = this.closest("table");
    var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
    checkboxes.forEach(cb => cb.checked = selectAllServices.checked);
});