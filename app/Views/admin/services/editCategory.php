
<?php

use App\Models\Service;

$publicErrors = array_filter($errors ?? [], fn($k) => is_numeric($k), ARRAY_FILTER_USE_KEY);
if (!empty($publicErrors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $key => $fieldErrors): ?>
                <?php if (is_numeric($key)): ?>
                    <li><?= htmlspecialchars($fieldErrors) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>
<div class="d-lg-none">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar">
        <i class="fa fa-bars"></i>
    </button>
</div>
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>

<form method="post">
    <?= csrf_field() ?>
    <div class="mt-5"><h4><?= __("basic_data") ?></h4></div>

    <div class="col mt-5">
        <div class="custom-part-with-border ">
            <div class="row">
                <div class="col-6">
                    <label for="fa_title"><?= __("title") ?> <span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" id="fa_title" name="fa_title" value="<?= htmlspecialchars(old('fa_title', $services->fa_title ?? '')) ?>">
                </div>
                <div class="col-6">
                    <label for="en_title"><?= __("english_title") ?> <span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" id="en_title" name="en_title" value="<?= htmlspecialchars(old('en_title', $services->en_title ?? '')) ?>">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-6">
                    <label class="form-check-label" for="serviceCategory"><?=__("add_as_service_category") ?></label>
                    <div class="form-check form-switch mt-4">
                        <input class="switch-input form-check-input" type="checkbox" role="switch"
                               id="serviceCategory" name="serviceCategory">
                    </div>
                </div>
                <div class="col-6">
                    <label for="category" class="form-label mb-0"><?= __('select_category') ?>:</label>
                    <select class="js-example-basic-single w-100"  id="category" name="category" >
                        <?php foreach ($allServices as $ser): ?>
                            <option value="<?= $ser->id ?>">
                                <?=($lang == "fa") ? htmlspecialchars($ser->fa_title) : htmlspecialchars($ser->en_title) ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary mt-5 px-5" type="submit"><?= __("add")?></button>
            </div>

        </div>
    </div>
</form>


<script>
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    const links = document.querySelectorAll('#filterLinks a');

    links.forEach(link => {
        link.classList.remove('text-primary');
        link.classList.add('text-dark');
        const href = new URL(link.href);
        if(href.searchParams.get('filter') === filter) {
            link.classList.remove('text-dark');
            link.classList.add('text-primary');
        }
    });
    var selectAllServices = document.getElementById("allServices");
    selectAllServices.addEventListener("change", function () {
        var table = this.closest("table");
        var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
        checkboxes.forEach(cb => cb.checked = selectAllServices.checked);
    });
    function toggleCategoryDisplay() {
        const switchInput = document.getElementById('serviceCategory');
        const categoryElement = document.getElementById('category');

        if (switchInput.checked) {
            categoryElement.style.display = 'block';
        } else {
            categoryElement.style.display = 'none';
        }
    }

    const switchInput = document.getElementById('serviceCategory');
    const categoryElement = document.getElementById('category');

    switchInput.addEventListener('click', function() {
        if (this.checked) {
            categoryElement.disabled = true;
            categoryElement.classList.add('selected');
        } else {
            categoryElement.disabled = false;
            categoryElement.classList.remove('selected');
        }
    });
    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });

</script>


</div>
</div>
</body>
<script src="<?= asset('/js/register-user.js') ?>"></script>
