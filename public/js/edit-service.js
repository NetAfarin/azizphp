$(document).ready(function () {
    $('.js-example-basic-single2').select2({
        dropdownParent: $('#editForm'),
        minimumResultsForSearch: Infinity,
    });
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    const links = $('#filterLinks a');

    links.each(function () {
        $(this).removeClass('text-primary').addClass('text-dark');

        const href = new URL(this.href);
        if (href.searchParams.get('filter') === filter) {
            $(this).removeClass('text-dark').addClass('text-primary');
        }
    });


    $('#editModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const serviceId = button.data('id');

        fetch(`${BASE_URL}/admin/services/getService/${serviceId}`)
            .then(res => res.json())
            .then(data => {

                $('#service_id').val(data.id);
                $('#fa_title_modal').val(data.fa_title);
                $('#en_title_modal').val(data.en_title);

                const checkBox = $('#checkBoxCategory');
                const categorySelect = $('select[name="service"]');

                // گرفتن کانتینر Select2
                const select2Container = categorySelect.next('.select2');

                const isCategory = parseInt(data.parent_id) === 0;

                checkBox.prop('checked', isCategory);

                if (isCategory) {
                    // چون کتگوری هست → سلکت کامل مخفی شود
                    categorySelect.prop('disabled', true);
                    select2Container.hide();
                } else {
                    // چون کتگوری نیست → سلکت نمایش داده شود و مقدار parent ست شود
                    categorySelect.prop('disabled', false);
                    categorySelect.val(data.parent_id).trigger('change.select2');
                    select2Container.show();
                }

                // وقتی چک‌باکس را تغییر میدهیم
                checkBox.off('change').on('change', function () {
                    if (this.checked) {
                        // مخفی کردن کامل سلکت
                        categorySelect.prop('disabled', true).val(null).trigger('change.select2');
                        select2Container.hide();
                    } else {
                        // نمایش مجدد سلکت
                        categorySelect.prop('disabled', false);
                        select2Container.show();
                    }
                });

            });
    });


    $('#editForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let serviceId = $('#service_id').val();
        let isCategory = $('#checkBoxCategory').is(':checked') ? 1 : 0;
        let categorySelect = $('.js-example-basic-single2');
        let categoryValue = categorySelect.val() || 0;

        formData.append('checkBoxCategory', isCategory);
        formData.append('category_modal', categoryValue);

        fetch(`${BASE_URL}/admin/services/update/${serviceId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.reload) {
                    location.reload();
                    return;
                }
                if (data.success) {
                    $('#editModal').modal('hide');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error services update:', error);
            });
    });


    const lang = $('html').attr('lang');
    const btn = $('.btn-search');

    if (lang === 'en') {
        btn.addClass('ltr-input');
    }

    $('#allServices').on('change', function () {
        const table = $(this).closest('table');
        const checkboxes = table.find("tbody input[type='checkbox']");
        checkboxes.prop('checked', this.checked);
    });


    window.toggleCategoryDisplay = function () {
        const switchInput = $('#serviceCategory');
        const categoryElement = $('#category');

        if (switchInput.is(':checked')) {
            categoryElement.show();
        } else {
            categoryElement.hide();
        }
    };


    const switchInput = $('#serviceCategory');
    const categoryElement = $('#category');

    switchInput.on('click', function () {
        if ($(this).is(':checked')) {
            categoryElement.prop('disabled', true).addClass('selected');
        } else {
            categoryElement.prop('disabled', false).removeClass('selected');
        }
    });


    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });


    $('.a').select2({
        dropdownParent: $('#editModal'),
        minimumResultsForSearch: Infinity,
    });

    $('.js-example-basic-single').select2({
        // dropdownParent: $('#editModal'),
        minimumResultsForSearch: Infinity,
    });

});
