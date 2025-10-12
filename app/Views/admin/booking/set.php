<?php
$days =APP_LANG=="fa"? [ 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه','شنبه'] : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI','SAT'];
$days=rotateArray( $days,$salon->start_day_of_week);
$todayIndex = intval(date('w'))+1-intval($salon->start_day_of_week);
$today = new DateTime('today');
$deltaToWeekStart = $todayIndex;
$weekStart = (clone $today)->modify("-{$deltaToWeekStart} days");
//vd($today);
$format = 'Y-m-d';
$weekDates = [];
for ($i = 0; $i < 7; $i++) {
    $d = (clone $weekStart)->modify("+{$i} days")->format($format);
    if (APP_LANG=="fa"){
        $explode = explode('-', $d);
        list($jy, $jm, $jd)=gregorian_to_jalali($explode[0], $explode[1], $explode[2]);
        $weekDates[] =  sprintf("%04d/%02d/%02d", $jy, $jm, $jd);
    }else{
        $weekDates[] = $d;
    }
}
?>

<label for="per_page" class="form-label mb-0"><?= __('service') ?>:</label>
<select id="employeeService" class="form-select w-auto">
    <?php foreach ($employeeServicesData as $opt):  ?>
        <option value="<?= $opt->id ?>" ><?= $opt->title ?></option>
    <?php endforeach; ?>
</select>
<div class="container mt-5 " dir="ltr">

    <div class="table-container">
    <span class="btn btn-info arrow-button" id="prevWeek"><span class="fa fa-arrow-left mx-1"></span><?= __('previous_week') ?></span>
        <h2 class="text-center mb-4" dir="<?= APP_LANG=="fa"?'rtl':'ltr' ?>"><?= __('weekly_reserve_table')." $user->first_name $user->last_name" ?></h2>
        <span class="btn btn-info arrow-button" id="nextWeek" ><?= __('next_week') ?><span class="fa fa-arrow-right mx-1"></span></span>
    </span>
    </div>
    <div class="dynamic-grid" id="grid-container"></div>

    <div class="text-center">
        <button class="btn btn-primary" id="reserveBtn">رزرو کردن</button>
    </div>
</div>
<?php
$servicePayload = array_map(function ($item) {
    if (is_object($item)) {
        if (method_exists($item, 'toArray')) return $item->toArray();
        if ($item instanceof JsonSerializable) return $item->jsonSerialize();
        return get_object_vars($item); // فقط publicها
    }
    return $item;
}, $employeeServicesData);
$scheduleData = (object)[
        'weekDates' => $weekDates,
        'todayIndex' =>$todayIndex ,
        'weekDays' => $days,
        'startTime' => $salon->start_time,
        'endTime' => $salon->end_time,
        'startTimeW1' => $salon->start_time_weekend_1,
        'endTimeW1' => $salon->end_time_weekend_1,
        'startTimeW2' => $salon->start_time_weekend_2,
        'endTimeW2' => $salon->end_time_weekend_2,
        'startTimeH' => $salon->start_time_holidays,
        'endTimeH' => $salon->end_time_holidays,
        'hasLaunchTime' => $user->has_launch_time,
        'launchTime' => $user->launch_time,
        'todayWord'=>__('today'),
        'w1Index'=>5,
        'w2Index'=>6,
        'hIndexes'=>[1,3],
        'serviceObj'=>$servicePayload,
];

?>


<script>
    window.scheduleConfig = <?= json_encode($scheduleData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="<?= asset('js/bookings-set.js') ?>"></script>

