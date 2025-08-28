$(document).ready(function() {
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
});
