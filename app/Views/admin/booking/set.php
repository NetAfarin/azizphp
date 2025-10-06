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
<script src="<?= asset('js/bookings-set.js') ?>"></script>

