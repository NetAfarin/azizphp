$(document).ready(function () {
    $('.perPageSelect').select2({
        minimumResultsForSearch: Infinity,
    });
    // $("#birth_date_picker").persianDatepicker({
    //     format: 'YYYY-MM-DD',
    //     autoClose: true,
    //     initialValue: false,
    //     altField: '#birth_date',
    //     altFormat: 'YYYY-MM-DD',
    //     calendar: {
    //         persian: { locale: 'fa' },
    //         gregorian: { locale: 'en' }
    //     }
    // });
    //
    // $("#birth_date_picker2").persianDatepicker({
    //     format: 'YYYY-MM-DD',
    //     autoClose: true,
    //     initialValue: false,
    //     altField: '#birth_date',
    //     altFormat: 'YYYY-MM-DD',
    //     calendar: {
    //         persian: { locale: 'fa' },
    //         gregorian: { locale: 'en' }
    //     }
    // });
    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    const links = document.querySelectorAll('#filterLinks a');

    links.forEach(link => {
        link.classList.remove('text-primary', 'text-dark');
        const href = new URL(link.href);
        if (href.searchParams.get('filter') === filter) {
            link.classList.add('text-primary');
        } else {
            link.classList.add('text-dark');
        }
    });
    $('#allReserve').on('change', function () {
        var table = $(this).closest("table");
        table.find("tbody input[type='checkbox']").prop("checked", this.checked);
    });
    const lang = $('html').attr('lang');
    if (lang === 'en') {
        $('.btn-search').addClass('ltr-input');
    }
});
