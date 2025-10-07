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

<?php
$scheduleSamples = [
    // 1) نمونه‌ی پایه (مشابه دیتای شما)
    (object)[
        'startTime'   => "10:00:00",
        'endTime'     => "19:00:00",
        'startTimeW1' => "10:00:00",
        'endTimeW1'   => "15:00:00",
        'startTimeW2' => "10:00:00",
        'endTimeW2'   => "16:30:00",
        'startTimeH'  => "10:00:00",
        'endTimeH'    => "19:30:00",
        'hasLaunchTime' => true,
        'launchTime'    => "12:00:00",
    ],

    // 2) شیفت عادی با ساعات کمی متفاوت
    (object)[
        'startTime'   => "09:30:00",
        'endTime'     => "18:30:00",
        'startTimeW1' => "09:30:00",
        'endTimeW1'   => "14:30:00",
        'startTimeW2' => "09:30:00",
        'endTimeW2'   => "17:00:00",
        'startTimeH'  => "10:00:00",
        'endTimeH'    => "18:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => "13:00:00",
    ],

    // 3) شیفت کوتاه‌تر در کل و بدون اضافه‌کاری
    (object)[
        'startTime'   => "08:00:00",
        'endTime'     => "16:00:00",
        'startTimeW1' => "08:00:00",
        'endTimeW1'   => "12:00:00",
        'startTimeW2' => "08:00:00",
        'endTimeW2'   => "14:30:00",
        'startTimeH'  => "08:00:00",
        'endTimeH'    => "16:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => "12:30:00",
    ],

    // 4) بدون زمان ناهار (برای تست شرط hasLaunchTime=false)
    (object)[
        'startTime'   => "11:00:00",
        'endTime'     => "19:00:00",
        'startTimeW1' => "11:00:00",
        'endTimeW1'   => "15:30:00",
        'startTimeW2' => "11:00:00",
        'endTimeW2'   => "17:00:00",
        'startTimeH'  => "11:00:00",
        'endTimeH'    => "19:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => null,   // عمداً null برای تست
    ],

    // 5) ناهار دیرهنگام
    (object)[
        'startTime'   => "10:00:00",
        'endTime'     => "19:00:00",
        'startTimeW1' => "10:00:00",
        'endTimeW1'   => "15:00:00",
        'startTimeW2' => "10:00:00",
        'endTimeW2'   => "16:00:00",
        'startTimeH'  => "10:00:00",
        'endTimeH'    => "19:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => "15:30:00",
    ],

    // 6) روز W1 تعطیل (با برابر بودن start/end برای تست «روز تعطیل»)
    (object)[
        'startTime'   => "09:00:00",
        'endTime'     => "17:00:00",
        'startTimeW1' => "00:00:00", // تعطیل
        'endTimeW1'   => "10:00:00",
        'startTimeW2' => "09:00:00",
        'endTimeW2'   => "13:00:00",
        'startTimeH'  => "09:00:00",
        'endTimeH'    => "17:30:00",
        'hasLaunchTime' => false,
        'launchTime'    => "12:15:00",
    ],

    // 7) دقایق نیم‌ساعته و رُند نبودن بعضی بازه‌ها
    (object)[
        'startTime'   => "08:30:00",
        'endTime'     => "17:00:00",
        'startTimeW1' => "08:30:00",
        'endTimeW1'   => "12:15:00",
        'startTimeW2' => "08:30:00",
        'endTimeW2'   => "15:45:00",
        'startTimeH'  => "09:15:00",
        'endTimeH'    => "17:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => "13:30:00",
    ],

    // 8) شیفت شبانه (شروع روز قبل، پایان روز بعد) — برای تست منطق عبور از نیمه‌شب
    (object)[
        'startTime'   => "22:00:00",
        'endTime'     => "06:00:00", // پایان روز بعد
        'startTimeW1' => "22:00:00",
        'endTimeW1'   => "02:00:00",
        'startTimeW2' => "22:00:00",
        'endTimeW2'   => "04:30:00",
        'startTimeH'  => "22:00:00",
        'endTimeH'    => "06:30:00",
        'hasLaunchTime' => true,
        'launchTime'    => "01:00:00",
    ],

    // 9) بازه‌ی مرزی (کل روز کاری)
    (object)[
        'startTime'   => "00:00:00",
        'endTime'     => "23:59:59",
        'startTimeW1' => "00:00:00",
        'endTimeW1'   => "12:00:00",
        'startTimeW2' => "12:00:00",
        'endTimeW2'   => "23:59:59",
        'startTimeH'  => "08:00:00",
        'endTimeH'    => "20:00:00",
        'hasLaunchTime' => true,
        'launchTime'    => "12:00:00",
    ],

    // 10) مورد عمداً نامعتبر برای تست اعتبارسنجی (endTime قبل از startTime، ناهار خارج از بازه)
    (object)[
        'startTime'   => "12:00:00",
        'endTime'     => "14:00:00", // نامعتبر: پایان قبل از شروع
        'startTimeW1' => "09:00:00",
        'endTimeW1'   => "10:45:00",
        'startTimeW2' => "13:00:00",
        'endTimeW2'   => "14:30:00", // نامعتبر: پایان قبل از شروع
        'startTimeH'  => "10:30:00",
        'endTimeH'    => "21:00:00", // نامعتبر
        'hasLaunchTime' => true,
        'launchTime'    => "13:30:00", // خارج از شیفت
    ],
];
$scheduleData=$scheduleSamples[9]
// اگر لازم دارید: echo json_encode($scheduleSamples, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
?>

<script>
    window.scheduleConfig = <?= json_encode($scheduleData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="<?= asset('js/bookings-set.js') ?>"></script>

