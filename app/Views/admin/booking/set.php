<?php
$hours = range(8, 17);

$days = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

$dayse = ['SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI'];
?>

<div class="container mt-5">
    <div class="table-container">
        <span class="btn btn-info arrow-button" id="prevWeek">&#8592; هفته قبل</span>
        <h2 class="text-center mb-4">جدول رزرو برای هفته</h2>
        <span class="btn btn-info arrow-button" id="nextWeek">هفته بعد &#8594;</span>
    </div>

    <div class="dynamic-grid" id="grid-container"></div>

    <div class="text-center">
        <button class="btn btn-primary" id="reserveBtn">رزرو کردن</button>
    </div>
</div>
<?php
$scheduleData = (object)[
        'startTime' => "10:00:00",
        'endTime' => "19:00:00",
        'startTimeW1' => "10:00:00",
        'endTimeW1' => "15:00:00",
        'startTimeW2' => "10:00:00",
        'endTimeW2' => "16:30:00",
        'startTimeH' => "10:00:00",
        'endTimeH' => "19:30:00",
        'hasLaunchTime' => true,
        'launchTime' => "12:00:00"
];
?>

<script>
    window.scheduleConfig = <?= json_encode($scheduleData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="<?= asset('js/bookings-set.js') ?>"></script>

