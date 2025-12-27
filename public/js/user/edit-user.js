$(document).ready(function () {
    const holidayCheckBox = $('.holiday-checkbox');

    holidayCheckBox.each(function () {
        var index = $(this).data('id');
        if ($(this).is(':checked')) {
            $('#startTime-' + index).val('').prop('disabled', true);
            $('#endTime-' + index).val('').prop('disabled', true);
        }
    });

    holidayCheckBox.change(function () {
        var index = $(this).data('id');
        $('#startTime-' + index).prop('disabled', $(this).is(':checked'));
        $('#endTime-' + index).prop('disabled', $(this).is(':checked'));
    });

    $('.js-example-basic-single').select2({
        minimumResultsForSearch: Infinity
    });

    $('.duration-select').select2({
        minimumResultsForSearch: Infinity
    });

    $('#multi-services').on('select2:select', function (e) {
        let id = e.params.data.id;
        let title = e.params.data.text;
        let durationOptionsHTML = '';

        if ($.isArray(durations)) {
            $.each(durations, function (index, item) {
                let itemId = item.id || item['id'];
                let itemTitle = item.title || item['title'];
                let enItemTitle = item.title || item['en_title'];
                let optionTitle = (lang == 'fa') ? itemTitle : enItemTitle;

                if (itemId) {
                    durationOptionsHTML += '<option value="' + itemId + '">' + optionTitle + '</option>';
                }
            });
        }

        if ($('#services_table_wrapper tbody tr[data-id="' + id + '"]').length === 0) {
            let row = '<tr data-id="' + id + '">' +
                '<td>' + title + '</td>' +
                '<td><input type="text" min="0" class="form-control" name="service_prices[' + id + ']" required></td>' +
                '<td>' +
                '<select name="service_durations[' + id + ']" class="duration-select-js" required>' +
                durationOptionsHTML +
                '</select>' +
                '</td>' +
                '<td><i class="fa fa-close remove-row icon-color" style="cursor:pointer;"></i></td>' +
                '</tr>';

            $('#services_table_wrapper tbody').append(row);
            $('#services_table_wrapper tbody tr[data-id="' + id + '"] .duration-select-js').select2({
                minimumResultsForSearch: Infinity
            });
        }
    });

    $('#multi-services').on('select2:unselect', function (e) {
        let id = e.params.data.id;
        $('#services_table_wrapper tbody tr[data-id="' + id + '"]').remove();
    });

    $(document).on('click', '.remove-row', function () {
        let row = $(this).closest('tr');
        let id = row.data('id');
        row.remove();
        let selectedValues = $('#multi-services').val();
        if (selectedValues && selectedValues.length > 0) {
            selectedValues = $.grep(selectedValues, function (value) {
                return value != id;
            });
            $('#multi-services').val(selectedValues).trigger('change');
        }
    });

    var switchData = $('#switchBox');
    var table = $('#workTable');
    var shiftTitle = $('#shiftTitle');
    var section = $('#employeeSection');
    var role = $('#userRole');

    function updateTableDisplay() {
        var selectedOption = role.find('option:selected');
        var optionId = selectedOption.val();

        if (optionId == "1") {
            switchData.show();
            shiftTitle.show();
            section.show();

            if (switchData.is(':checked')) {
                table.hide();
            } else {
                table.show();
            }
        } else {
            switchData.hide();
            table.hide();
            shiftTitle.hide();
            section.hide();
        }
    }

    switchData.change(updateTableDisplay);
    role.change(updateTableDisplay);
    updateTableDisplay();
});