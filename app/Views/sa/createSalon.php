<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/services/categories" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<?php
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
<form method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label"><?= __('salon_name') ?></label>
        <input type="text" name="salon_name" class="form-control"
               value="<?= htmlspecialchars(old('salon_name')) ?>">
        <?php if (!empty($errors['salon_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['salon_name'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('manager_name') ?></label>
        <input type="text" name="manager" class="form-control"
               value="<?= htmlspecialchars(old('manager')) ?>">
        <?php if (!empty($errors['manager'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['manager'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('username') ?></label>
        <input type="text" name="username" class="form-control"
               value="<?= htmlspecialchars(old('username')) ?>">
        <?php if (!empty($errors['username'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['username'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('manager_email') ?></label>
        <input type="text" name="manager_email" class="form-control"
               value="<?= htmlspecialchars(old('manager_email')) ?>">
        <?php if (!empty($errors['manager_email'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['manager_email'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('manager_mobile') ?></label>
        <input type="text" name="manager_mobile" class="form-control"
               value="<?= htmlspecialchars(old('manager_mobile')) ?>">
        <?php if (!empty($errors['manager_mobile'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['manager_mobile'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('salon_link') ?></label>
        <input type="text" name="link_name" class="form-control"
               value="<?= htmlspecialchars(old('link_name')) ?>">
        <?php if (!empty($errors['link_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['link_name'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('plan_type') ?></label>
        <select class="form-select" name="plan">
            <option>نوع پلن را انتخاب کنید</option>
            <?php foreach ($planeType as $p): ?>
                <option value="<?= $p->id ?>"><?= $p->title ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['salon_plane'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['salon_plane'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('salon_address') ?></label>
        <input type="text" name="postal_address" class="form-control"
               value="<?= htmlspecialchars(old('postal_address')) ?>">
        <?php if (!empty($errors['postal_address'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['postal_address'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('about_us') ?></label>
        <input type="text" name="about_us" class="form-control"
               value="<?= htmlspecialchars(old('about_us')) ?>">
        <?php if (!empty($errors['about_us'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['about_us'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('salon_start_day') ?></label>
         <select class="form-select" id="daySelect" name="start_day_of_week">
             <option value="1">شنبه</option>
             <option value="2">یکشنبه</option>
             <option value="3">دوشنبه</option>
             <option value="4">سه شنبه</option>
             <option value="5">چهارشنبه</option>
             <option value="6">پنج شنبه</option>
             <option value="7">جمعه</option>
         </select>
        <?php if (!empty($errors['about_us'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['about_us'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="row mb-3"  >
        <div class="col-6">
            <label class="form-label"><?= __('start_time') ?></label>
            <input type="time" name="start_time" class="form-control"
                   value="<?= htmlspecialchars(old('weekend')) ?>">
            <?php if (!empty($errors['start_time'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['start_time'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label class="form-label"><?= __('end_time') ?></label>
            <input type="time" name="end_time" class="form-control"
                   value="<?= htmlspecialchars(old('weekend')) ?>">
            <?php if (!empty($errors['end_time'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['end_time'][0]) ?></div>
            <?php endif; ?>
        </div>

    </div>
    <div class="row mb-3">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="is_active" name="active_weekend_1">
            <label class="form-check-label" for="is_active" id="status_label">تعطیل</label>
        </div>
        <div class="col-6">
            <label for="start_time_weekend_1" class="form-label" id="start_time_weekend_1"></label>
            <input type="time" name="start_time_weekend_1" class="form-control" id="start_time_weekend_1 aa"
                   value="<?= htmlspecialchars(old('start_time_weekend_1')) ?>">
            <?php if (!empty($errors['start_time_weekend_1'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['start_time_weekend_1'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label for="end_time_weekend_1" class="form-label" id="end_time_weekend_1"></label>
            <input type="time" name="end_time_weekend_1" class="form-control" id="end_time_weekend_1"
                   value="<?= htmlspecialchars(old('end_time_weekend_1')) ?>">
            <?php if (!empty($errors['end_time_weekend_1'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['end_time_weekend_1'][0]) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="is_active2" name="active_weekend_2">
            <label class="form-check-label" for="is_active" id="status_label2">تعطیل</label>
        </div>
        <div class="col-6">
            <label for="start_time_weekend_2" class="form-label" id="start_time_weekend_2"></label>
            <input type="time" name="start_time_weekend_2" class="form-control" id="start_time_weekend_2"
                   value="<?= htmlspecialchars(old('start_time_weekend_2')) ?>">
            <?php if (!empty($errors['start_time_weekend_2'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['start_time_weekend_1'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label for="end_time_weekend_2" class="form-label" id="end_time_weekend_2"></label>
            <input type="time" name="end_time_weekend_2" class="form-control" id="end_time_weekend_2"
                   value="<?= htmlspecialchars(old('end_time_weekend_2')) ?>">
            <?php if (!empty($errors['end_time_weekend_2'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['end_time_weekend_2'][0]) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-3" >
        <div class="form-check form-switch mb-3" >
            <input class="form-check-input" type="checkbox" id="is_active" name="active_holidays">
            <label class="form-check-label" for="is_active" id="status_label">تعطیل</label>
        </div>
            <div class="col-6">
                <label class="form-label"><?= __('start_time_holiday') ?></label>
                <input type="time" name="start_time_holidays" class="form-control"
                       value="<?= htmlspecialchars(old('start_time_holidays')) ?>">
                <?php if (!empty($errors['start_time_holidays'])): ?>
                    <div class="text-danger small">
    <?= htmlspecialchars($errors['start_time_holidays'][0]) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-6">
                <label class="form-label"><?= __('end_time_holiday') ?></label>
                <input type="time" name="end_time_holidays" class="form-control"
                       value="<?= htmlspecialchars(old('end_time_holidays')) ?>">
                <?php if (!empty($errors['end_time_holidays'])): ?>
                    <div class="text-danger small">
    <?= htmlspecialchars($errors['end_time_holidays'][0]) ?></div>
                <?php endif; ?>
            </div>

        </div>
    <div class="mb-3">
        <label for="max_reserve_day" class="form-label"><?= __("max_reserve_day")?></label>
        <input type="text" name="max_reserve_day" class="form-control" id="	max_reserve_day"
               value="<?= htmlspecialchars(old('max_reserve_day')) ?>">
        <?php if (!empty($errors['end_time_weekend_1'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['end_time_weekend_1'][0]) ?></div>
        <?php endif; ?>
    </div>
    <button class="btn btn-primary"><?= __('save_changes') ?></button>
    <a href="<?= BASE_URL."/".SALON_ID ?>/salons" class="btn btn-danger"><?= __('cancel') ?></a>
</form>

<script>

    function updateWeekendDays() {
        let daySelect = document.getElementById('daySelect');
        let selectedValue = parseInt(daySelect.value);
        let options = daySelect.options;

        let weekend2 = selectedValue - 1;
        let weekend1 = selectedValue - 2;
        if (weekend2 < 1) weekend2 = 7 + weekend2;
        if (weekend1 < 1) weekend1 = 7 + weekend1;

        let weekend1Name = options[weekend1 - 1].text;
        let weekend2Name = options[weekend2 - 1].text;

        document.getElementById('start_time_weekend_1').textContent = `شروع ساعت کاری روز ${weekend1Name}`;
        document.getElementById('end_time_weekend_1').textContent = `پایان ساعت کاری روز ${weekend1Name}`;
        document.getElementById('start_time_weekend_2').textContent = `شروع ساعت کاری روز ${weekend2Name}`;
        document.getElementById('end_time_weekend_2').textContent = `پایان ساعت کاری روز ${weekend2Name}`;

    }

    document.getElementById('daySelect').addEventListener('change', updateWeekendDays);
    document.addEventListener('DOMContentLoaded', function() {

        updateWeekendDays();
    });
    const checkbox = document.getElementById('is_active');
    const label = document.getElementById('status_label');
    const checkbox2 = document.getElementById('is_active2');
    const label2 = document.getElementById('status_label2');
    const checkbox3 = document.getElementById('is_active3');
    const label3 = document.getElementById('status_label3');
    const timeInput = document.getElementById("start_time_weekend_1");

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            label.textContent = 'باز';
            label.textContent = 'باز';
            document.getElementById("start_time_weekend_1").disabled = false;

        } else {
            label.textContent = 'تعطیل';
            document.getElementById("start_time_weekend_1").disabled = true;

        }
    });
    checkbox2.addEventListener('change', function() {
        if (this.checked) {
            label2.textContent = 'باز';
            label2.textContent = 'باز';
        } else {
            label2.textContent = 'تعطیل';
        }
    });
    checkbox3.addEventListener('change', function() {
        if (this.checked) {
            label3.textContent = 'باز';
            label3.textContent = 'باز';
        } else {
            label3.textContent = 'تعطیل';
        }
    });

</script>