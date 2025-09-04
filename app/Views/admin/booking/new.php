<form method="post" action="<?= BASE_URL ?>/admin/booking/save">
    <?= csrf_field() ?>


    <div class="mb-3">
        <label class="form-label"><?=__("choose_customer")?></label>
        <select name="user_id"  id="customer_select"  class="form-select" required>
        <option value="0"><?=__("choose_customer_placeholder")?></option>
            <?php foreach ($customers as $u): ?>
                <option value="<?= $u->id ?>">
                    <?= htmlspecialchars($u->first_name . ' ' . $u->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label"><?=__("choose_employee")?></label>
        <select name="employee_id" id="employee_select" class="form-select" required>
        <option value="0"><?=__("choose_employee_placeholder")?></option>
            <?php foreach ($employees as $e): ?>
                <option value="<?= $e->id ?>">
                    <?= htmlspecialchars($e->first_name . ' ' . $e->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label"><?=__("choose_service")?></label>
        <select name="service_id" id="service_select" class="form-select" required disabled>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label" for=""><?=__("choose_duration")?></label>
        <select name="duration" class="form-select" id="duration_select" required disabled>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">تاریخ و ساعت رزرو</label>
        <input type="datetime-local" name="visit_time" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">ثبت رزرو</button>
    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-danger">انصراف</a>
</form>
<script src="<?= asset('/public/js/booking.js') ?>"></script>
