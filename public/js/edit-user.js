    const userTypeSelect = document.getElementById('user_type_select');
    const employeeServicesSection = document.getElementById('employee_services_section');
    const services_table_wrapper = document.getElementById('services_table_wrapper');
    const services_table = document.getElementById('services_table');
    const serviceSelectJs = document.querySelector('.js-example-basic-multiple');

    function toggleEmployeeServices() {
    const selectedType = userTypeSelect.options[userTypeSelect.selectedIndex].text.toLowerCase();
    if (selectedType.includes('کارمند') || selectedType.includes('employee')) {
    employeeServicesSection.style.display = 'block';
    services_table_wrapper.style.display = 'block';
    $('.js-example-basic-multiple').select2();
} else {
    employeeServicesSection.style.display = 'none';
    services_table_wrapper.style.display = 'none';
    const tbody = services_table.querySelector('tbody');
    if (tbody) {
    tbody.innerHTML = '';
}
    if (serviceSelectJs) {
    for (let i = 0; i < serviceSelectJs.options.length; i++) {
    serviceSelectJs.options[i].selected = false;
}

    if (typeof $ !== 'undefined' && $(serviceSelectJs).hasClass("select2-hidden-accessible")) {
    $(serviceSelectJs).val(null).trigger('change');
}
}
}
}

    userTypeSelect.addEventListener('change', toggleEmployeeServices);
    toggleEmployeeServices();

    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
    $(document).ready(function() {
    const serviceSelect = $('.js-example-basic-multiple');
    const tableWrapper = $('#services_table_wrapper');
    const tbody = $('#services_table tbody');

    serviceSelect.on('select2:select', function(e) {
    tableWrapper.show();
    const serviceId = e.params.data.id;
    const serviceText = e.params.data.text;
    if ($('#row-' + serviceId).length === 0) {
    const durationOptions = `
    <?php foreach ($durations as $d): ?>
        <option value="<?= $d->id ?>"><?= htmlspecialchars($d->title) ?></option>
    <?php endforeach; ?>
`;
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
    tbody.append(row);
}
});


    serviceSelect.on('select2:unselect', function(e) {
    const serviceId = e.params.data.id;
    $('#row-' + serviceId).remove();

    if (tbody.children().length === 0) {
    tableWrapper.hide();
}
});


    $(document).on('click', '.remove-row', function() {
    const serviceId = $(this).data('id');

    serviceSelect.val(serviceSelect.val().filter(id => id != serviceId)).trigger('change');

    $('#row-' + serviceId).remove();

    if (tbody.children().length === 0) {
    tableWrapper.hide();
}
});
});

