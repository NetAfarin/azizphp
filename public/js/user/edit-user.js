// $(document).ready(function () {
//     const durationOptions = window.durations.map(d =>
//         `<option value="${d.id}">${d.title}</option>`
//     ).join('');
//     const $userTypeSelect          = $('#user_type_select');
//     const $employeeServicesSection = $('#employee_services_section');
//     const $servicesTableWrapper    = $('#services_table_wrapper');
//     const $servicesTable           = $('#services_table');
//     const $serviceSelect           = $('.js-example-basic-multiple');
//
//
//     function toggleEmployeeServices() {
//         const selectedText = $userTypeSelect.find('option:selected').text().toLowerCase();
//
//         if (selectedText.includes('کارمند') || selectedText.includes('employee')) {
//
//             $employeeServicesSection.show();
//             $servicesTableWrapper.show();
//             $serviceSelect.select2();
//         } else {
//             $employeeServicesSection.hide();
//             $servicesTableWrapper.hide();
//
//             $servicesTable.find('tbody').empty();
//
//             $serviceSelect.val(null).trigger('change');
//         }
//     }
//
//     toggleEmployeeServices();
//
//     $userTypeSelect.on('change', toggleEmployeeServices);
//
//     $serviceSelect.select2();
//
//     $serviceSelect.on('select2:select', function (e) {
//         $servicesTableWrapper.show();
//
//         const serviceId   = e.params.data.id;
//         const serviceText = e.params.data.text;
//
//         if ($('#row-' + serviceId).length === 0) {
//             const row = `
//                 <tr id="row-${serviceId}">
//                     <td>${serviceText}</td>
//                     <td>
//                         <input type="number" step="0.01" min="0"
//                                class="form-control"
//                                name="service_prices[${serviceId}]"
//                                required>
//                     </td>
//                     <td>
//                         <select name="service_durations[${serviceId}]" class="form-select" required>
//                             ${durationOptions}
//                         </select>
//                     </td>
//                     <td>
//                         <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${serviceId}">✖</button>
//                     </td>
//                 </tr>`;
//             $servicesTable.find('tbody').append(row);
//         }
//     });
//
//     $serviceSelect.on('select2:unselect', function (e) {
//         const serviceId = e.params.data.id;
//         $('#row-' + serviceId).remove();
//
//         if ($servicesTable.find('tbody').children().length === 0) {
//             $servicesTableWrapper.hide();
//         }
//     });
//
//     $(document).on('click', '.remove-row', function () {
//         const serviceId = $(this).data('id');
//
//         let values = $serviceSelect.val() || [];
//         values = values.filter(id => id != serviceId);
//         $serviceSelect.val(values).trigger('change');
//
//         $('#row-' + serviceId).remove();
//
//         if ($servicesTable.find('tbody').children().length === 0) {
//             $servicesTableWrapper.hide();
//         }
//     });
// });
$(document).ready(function () {
    // const holidayCheckBox = $('.holiday-checkbox');
    // holidayCheckBox.each(function() {
    //     var index = $(this).data('id');
    //     if ($(this).is(':checked')) {
    //         $('#startTime-' + index).val('').prop('disabled', true);
    //         $('#endTime-' + index).val('').prop('disabled', true);
    //     }
    // });
    // holidayCheckBox.change(function() {
    //     var index = $(this).data('id');
    //     $('#startTime-' + index).prop('disabled', $(this).is(':checked'));
    //     $('#endTime-' + index).prop('disabled', $(this).is(':checked'));
    // });
    // $('.js-example-basic-single').select2({
    //     minimumResultsForSearch: Infinity,
    // });
    // $('.duration-select').select2({
    //     minimumResultsForSearch: Infinity,
    // });
    // $('#multi-services').on('select2:select', function(e) {
    //     let id = e.params.data.id;
    //     let title = e.params.data.text;
    //
    //     let durationOptionsHTML = `<?php foreach ($durations as $i): ?>
    //           <option value="<?= $i->id ?>"><?= $i->title ?></option>
    //              <?php endforeach; ?>`;
    //     if($('#services_table_wrapper tbody tr[data-id="'+id+'"]').length === 0) {
    //
    //         let row = `<tr data-id="${id}">
    //         <td>${title}</td>
    //         <td><input type="text" min="0" class="form-control" name="service_prices[${id}]" required></td>
    //         <td>
    //             <select name="service_durations[${id}]" class="duration-select-js" required>
    //                 ${durationOptionsHTML}
    //             </select>
    //         </td>
    //         <td><i class="fa fa-close remove-row icon-color" style="cursor:pointer;"></i></td>
    //     </tr>`;
    //         $('#services_table_wrapper tbody').append(row);
    //         $('#services_table_wrapper tbody tr[data-id="' + id + '"] .duration-select-js').select2({
    //             minimumResultsForSearch: Infinity,
    //         });
    //     }
    //
    // });
    //
    // $(document).on('click', '.remove-row', function () {
    //     let row = $(this).closest('tr');
    //     let id = row.data('id');
    //     row.remove();
    //     let selectedValues = $('#multi-services').val();
    //     selectedValues = selectedValues.filter(v => v != id);
    //
    //     $('#multi-services').val(selectedValues).trigger('change');
    // });
    //
    // $('#multi-services').on('select2:unselect', function (e) {
    //     let id = e.params.data.id;
    //     $('#services_table_wrapper tbody tr[data-id="'+id+'"]').remove();
    // });
    const holidayCheckBox = $('.holiday-checkbox');
    holidayCheckBox.each(function() {
        var index = $(this).data('id');
        if ($(this).is(':checked')) {
            $('#startTime-' + index).val('').prop('disabled', true);
            $('#endTime-' + index).val('').prop('disabled', true);
        }
    });
    holidayCheckBox.change(function() {
        var index = $(this).data('id');
        $('#startTime-' + index).prop('disabled', $(this).is(':checked'));
        $('#endTime-' + index).prop('disabled', $(this).is(':checked'));
    });
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity,
    });
    $('.duration-select').select2({
        minimumResultsForSearch: Infinity,
    });
    $('#multi-services').on('select2:select', function(e) {
        let id = e.params.data.id;
        let title = e.params.data.text;

        // ساخت options از داده‌های موجود
        let durationOptionsHTML = '';

        if (Array.isArray(durationOptions)) {
            durationOptions.forEach(function(item) {
                // چون item یک آرایه است، باید به صورت item['key'] به آن دسترسی پیدا کنیم
                let itemId = item.id || item['id'];
                let itemTitle = item.title || item['title'];

                // اگر زبان انگلیسی است ولی en_title ندارید، از title استفاده کنید
                let optionTitle = (currentLang == 'fa') ? itemTitle : itemTitle;
                // یا اگر می‌خواهید فقط برای انگلیسی نمایش متفاوت باشد:
                // let optionTitle = itemTitle; // همیشه از title فارسی استفاده کن

                if (itemId) {
                    durationOptionsHTML += `<option value="${itemId}">${optionTitle}</option>`;
                }
            });
        }

        if($('#services_table_wrapper tbody tr[data-id="'+id+'"]').length === 0) {
            let row = `<tr data-id="${id}">
            <td>${title}</td>
            <td><input type="text" min="0" class="form-control" name="service_prices[${id}]" required></td>
            <td>
                <select name="service_durations[${id}]" class="duration-select-js" required>
                    ${durationOptionsHTML}
                </select>
            </td>
            <td><i class="fa fa-close remove-row icon-color" style="cursor:pointer;"></i></td>
        </tr>`;

            $('#services_table_wrapper tbody').append(row);
            $('#services_table_wrapper tbody tr[data-id="' + id + '"] .duration-select-js').select2({
                minimumResultsForSearch: Infinity,
            });
        }
    });

    $('#multi-services').on('select2:unselect', function (e) {
        let id = e.params.data.id;
        $('#services_table_wrapper tbody tr[data-id="'+id+'"]').remove();
    });
    var switchData = document.getElementById("switchBox");
    var table = document.getElementById("workTable");
    var shiftTitle = document.getElementById("shiftTitle");
    var section = document.getElementById("employeeSection");
    var role = $('#userRole');

    function updateTableDisplay() {
        var selectedOption = role.find('option:selected');
        var optionId = selectedOption.val();

        if (optionId == "1") {
            switchBox.style.display = "block";
            shiftTitle.style.display = "block";
            section.style.display = "block";

            if (switchData.checked) {
                table.style.display = "none";
            } else {
                table.style.display = "block";
            }
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