<form method="post" action="<?= BASE_URL ?>/admin/booking/save">
    <?= csrf_field() ?>


    <div class="mb-3">
        <label class="form-label">انتخاب مشتری</label>
        <select name="user_id"  id="customer_select"  class="form-select" required>
            <?php foreach ($customers as $u): ?>
                <option value="<?= $u->id ?>">
                    <?= htmlspecialchars($u->first_name . ' ' . $u->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">انتخاب کارمند</label>
        <select name="employee_id" id="employee_select" class="form-select" required>
            <?php foreach ($employees as $e): ?>
                <option value="<?= $e->id ?>">
                    <?= htmlspecialchars($e->first_name . ' ' . $e->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">انتخاب سرویس</label>
        <select name="service_id" id="service_select" class="form-select" required>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s->id ?>">
                    <?= htmlspecialchars($s->fa_title) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label>مدت زمان سرویس</label>
        <input type="time" name="service_time" id="service_time" value="01:30" step="1800">
<!--        <label class="form-label">مدت زمان</label>-->
<!--        <select name="duration_id" class="form-select" required>-->
<!--            --><?php //foreach ($durations as $d): ?>
<!--                <option value="--><?php //= $d->id ?><!--">--><?php //= htmlspecialchars($d->title) ?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
    </div>


    <div class="mb-3">
        <label class="form-label">تاریخ و ساعت رزرو</label>
        <input type="datetime-local" name="visit_time" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">ثبت رزرو</button>
    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-danger">انصراف</a>
</form>
<script src="<?= asset('/public/js/booking.js') ?>"></script>
