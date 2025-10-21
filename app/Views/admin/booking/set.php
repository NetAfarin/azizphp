<?php
$days =APP_LANG=="fa"? [ 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه','شنبه'] : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI','SAT'];
$days=rotateArray( $days,$salon->start_day_of_week);

$i1 = intval(date('w')) - intval($salon->start_day_of_week);
$todayIndex = $i1<0? $i1+7:$i1;
$today = new DateTime('today');
$deltaToWeekStart = $todayIndex;
$weekStart = (clone $today)->modify("-{$deltaToWeekStart} days");
$format = 'Y-m-d';
$weekDates = [];
$weekDatesForShow = [];
$maxWeek = 4;
for ($i = 0; $i < 7* $maxWeek; $i++) {
    $d = (clone $weekStart)->modify("+{$i} days")->format($format);
    $weekDates[] = $d;
    if (APP_LANG=="fa"){
        $explode = explode('-', $d);
        list($jy, $jm, $jd)=gregorian_to_jalali($explode[0], $explode[1], $explode[2]);
        $weekDatesForShow[] =  sprintf("%04d/%02d/%02d", $jy, $jm, $jd);
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
    <button class="btn btn-info arrow-button" id="prevWeek" disabled><span class="fa fa-arrow-left mx-1"></span><?= __('previous_week') ?></button>
        <h2 class="text-center mb-4 no-select" dir="<?= APP_LANG=="fa"?'rtl':'ltr' ?>"><?= __('weekly_reserve_table')." $user->first_name $user->last_name" ?></h2>
        <button class="btn btn-info arrow-button" id="nextWeek" <?= $maxWeek<=1?"disabled":"" ?>><?= __('next_week') ?><span class="fa fa-arrow-right mx-1"></span></button>
    </span>
    </div>
    <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" id="startDate" name="startDate" value="<?= $weekDates[0] ?>">
        <input type="hidden" id="endDate" name="endDate" value="<?= $weekDates[6] ?>">
    <div class="dynamic-grid" id="grid-container"></div>

    <div class="text-center">
        <input type="submit" class="btn btn-primary" id="reserveBtn" disabled value="ذخیره تغییرات" />
    </div>
    </form>
</div>
<?php
$servicePayload = array_map(function ($item) {
    if (is_object($item)) {
        if (method_exists($item, 'toArray')) return $item->toArray();
        if ($item instanceof JsonSerializable) return $item->jsonSerialize();
        return get_object_vars($item);
    }
    return $item;
}, $employeeServicesData);

$prebookingPayload = array_map(function ($item) {
    if (APP_LANG=="fa"){
        $explode = explode('-', $item->date);
        list($jy, $jm, $jd)=gregorian_to_jalali($explode[0], $explode[1], $explode[2]);
        $item->date =  sprintf("%04d/%02d/%02d", $jy, $jm, $jd);
    }
    $item->time=substr($item->time, 0, 5);
    if (is_object($item)) {
        if (method_exists($item, 'toArray')) return $item->toArray();
        if ($item instanceof JsonSerializable) return $item->jsonSerialize();
        return get_object_vars($item);
    }

    return $item;
}, $prebookingList);
$scheduleData = (object)[
        'weekDates' => $weekDates,
        'weekDatesForShow' => $weekDatesForShow,
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
        'prebookingList'=>$prebookingPayload,
];
?>


<script>
    window.scheduleConfig = <?= json_encode($scheduleData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="<?= asset('js/bookings-set.js') ?>"></script>

