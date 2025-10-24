
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
    <div class="row mt-5">
        <div class="col-6">
            <label for="name"><?= __("salon_name") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="col-6">
            <label for="lastname"><?= __("branch_code") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="lastname" name="lastname">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label for="address"><?= __("postal_address") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="user-role" name="address">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label for="about_us"><?= __("about_us") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="about_us" name="about_us">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row mt-5"><h4><?= __("manager_information") ?></h4></div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="manager_name"><?= __("first_name") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="manager_name" name="manager_name">
        </div>
        <div class="col-6">
            <label for="manager_lastname"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="manager_lastname" name="manager_lastname">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="manager_phone_number"><?= __("phone_number") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="manager_phone_number" name="manager_phone_number">
        </div>
        <div class="col-6">
            <label for="manager_email"><?= __("email") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="manager_email" name="manager_email">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row mt-5"><h4><?= __("work_time") ?></h4></div>
    <div class="row mt-5"><h5-><?= __("start_work_week") ?></h5-></div>
    <div class="row mt-5">
        <div class="col-12">
            <?php foreach ($dayWeek as $index => $day):?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input radio-button " type="radio" name="start_day_of_week"
                           id="<?= $index ?>" value="<?= $index ?>"
                           onchange="handleHolidayChange(this)">
                    <label class="form-check-label" for="<?= $index ?>"><?= $day ?></label>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="start_time_work"><?= __("start_time_work") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="start_time_work" name="start_time_work">
        </div>
        <div class="col-6">
            <label for="end_time_work"><?= __("end_time_work") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="end_time_work" name="end_time_work">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label class="form-check-label" for="active_weekend_1" id="start-weekend1"></label>
            <div class="form-check form-switch">
                <input class="switch-input form-check-input" type="checkbox" role="switch" id="active_weekend_1" name="followSalon">
            </div>
        </div>
    </div>
    <div id="sectionWeekend1">
        <div
                class="row mt-5">
            <div class="col-6">
                <label for="start_time_weekend_1" id="start_time_work_at_weekend1"><span class="bullet-color"> *</span></label>
                <input type="text" class="form-control" id="start_time_weekend_1" name="start_time_weekend_1">
            </div>
            <div class="col-6">
                <label for="end_time_weekend_1" id="end_time_work_at_weekend1"><span class="bullet-color"> *</span></label>
                <input type="text" class="form-control" id="end_time_weekend_1" name="end_time_weekend_1">
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label class="form-check-label" for="active_weekend_2" id="end-weekend1"></label>
            <div class="form-check form-switch">
                <input class="switch-input form-check-input" type="checkbox" role="switch" id="active_weekend_2" name="followSalon" >
            </div>
        </div>
    </div>
    <div id="sectionWeekend2">
        <div class="row mt-5">
            <div class="col-6">
                <label for="start_time_weekend_2" id="start_time_work_at_weekend2"><span class="bullet-color"> *</span></label>
                <input type="text" class="form-control" id="start_time_weekend_2" name="start_time_weekend_2">
            </div>
            <div class="col-6">
                <label for="end_time_weekend_2" id="end_time_work_at_weekend2"><span class="bullet-color"> *</span></label>
                <input type="text" class="form-control" id="end_time_weekend_2" name="end_time_weekend_2">
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label class="form-check-label" for="switchBox"><?= __("holiday")?></label>
            <div class="form-check form-switch">
                <input class="switch-input form-check-input" type="checkbox" role="switch" id="active_holidays" name="followSalon" >
            </div>
        </div>
    </div>
<div id="sectionHoliday">
    <div class="row mt-5">
        <div class="col-6">
            <label for="start_time_holidays"><?= __("start_time_work_at_holiday") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="start_time_holidays" name="start_time_holidays">
        </div>
        <div class="col-6">
            <label for="end_time_holidays"><?= __("end_time_work_at_holiday") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="end_time_holidays" name="end_time_holidays">
        </div>
    </div>

</div>
    <button class="btn btn-primary mt-5 px-5" type="submit"><?= __("add")?></button>
</form>


</div>
</div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultCheckbox = document.getElementById("1");
        const active_weekend_1 = document.getElementById("active_weekend_1");
        const sectionWeekend1 = document.getElementById("sectionWeekend1");
        const active_weekend_2= document.getElementById("active_weekend_2");
        const sectionWeekend2 = document.getElementById("sectionWeekend2");
        const active_holidays= document.getElementById("active_holidays");
        const sectionHoliday = document.getElementById("sectionHoliday");
        sectionWeekend1.style.display = active_weekend_1.checked ? "block" : "none";
        sectionWeekend2.style.display = active_weekend_2.checked ? "block" : "none";
        sectionHoliday.style.display = active_holidays.checked ? "block" : "none";

        active_weekend_1.addEventListener("change", function() {
            sectionWeekend1.style.display = this.checked ? "block" : "none";
        });
        active_weekend_2.addEventListener("change", function() {
            sectionWeekend2.style.display = this.checked ? "block" : "none";
        });
         active_holidays.addEventListener("change", function() {
            sectionHoliday.style.display = this.checked ? "block" : "none";
        });

        if (defaultCheckbox) {
            defaultCheckbox.checked = true;
            handleHolidayChange(defaultCheckbox);
        }
    });

    function getWeekendDays(selectedDay) {
        const APP_LANG = '<?php echo $_SESSION['lang'] ?? 'fa'; ?>';
        const weekendTranslations = {
            en: {
                '1': ['Thursday', 'Friday'],
                '2': ['Friday', 'Saturday'],
                '3': ['Saturday', 'Sunday'],
                '4': ['Sunday', 'Monday'],
                '5': ['Monday', 'Tuesday'],
                '6': ['Tuesday', 'Wednesday'],
                '7': ['Wednesday', 'Thursday']
            },
            fa: {
                '1': ['پنجشنبه', 'جمعه'],
                '2': ['جمعه', 'شنبه'],
                '3': ['شنبه', 'یکشنبه'],
                '4': ['یکشنبه', 'دوشنبه'],
                '5': ['دوشنبه', 'سه‌شنبه'],
                '6': ['سه‌شنبه', 'چهارشنبه'],
                '7': ['چهارشنبه', 'پنجشنبه']
            }
        };

        const weekendMap = weekendTranslations[APP_LANG] || weekendTranslations.fa;

        return {
            selectedDay: selectedDay,
            weekend: weekendMap[selectedDay] || "روز انتخابی معتبر نیست"
        };
    }

    function handleHolidayChange(checkbox) {
        const TRANSLATIONS = {
            start_time_work_at_weekend1: '<?= __("start_time_work_at_weekend1") ?>',
            end_time_work_at_weekend1: '<?= __("end_time_work_at_weekend1") ?>',
            start_time_work_at_weekend2: '<?= __("start_time_work_at_weekend2") ?>',
            end_time_work_at_weekend2: '<?= __("end_time_work_at_weekend2") ?>'
        };
        const APP_LANG = '<?php echo $_SESSION['lang'] ?? 'fa'; ?>';
        var startWeekEnd1 = document.getElementById("start-weekend1");
        var endWeekEnd2 = document.getElementById("end-weekend1");
        var startWeekEnd1Lbl = document.getElementById("start_time_work_at_weekend1");
        var endWeekEnd1Lbl = document.getElementById("end_time_work_at_weekend1");
        var startWeekEnd2Lbl = document.getElementById("start_time_work_at_weekend2");
        var endWeekEnd2Lbl = document.getElementById("end_time_work_at_weekend2");
        const id = checkbox.id;
        var weekendDays = getWeekendDays(id);
        var weekend = weekendDays.weekend;
        startWeekEnd1.textContent = (APP_LANG === "en" ? "Day " : "روز ") + weekend[0];
        endWeekEnd2.textContent = (APP_LANG === "en" ? "Day " : "روز ") + weekend[1];

        startWeekEnd1Lbl.textContent = TRANSLATIONS.start_time_work_at_weekend1.replace('%s', weekend[0]);
        endWeekEnd1Lbl.textContent = TRANSLATIONS.end_time_work_at_weekend1.replace('%s', weekend[0]);
        startWeekEnd2Lbl.textContent = TRANSLATIONS.start_time_work_at_weekend2.replace('%s', weekend[1]);
        endWeekEnd2Lbl.textContent = TRANSLATIONS.end_time_work_at_weekend2
            .replace('%s', weekend[1]);
    }
</script>
<script src="<?= asset('/js/register-user.js') ?>"></script>
