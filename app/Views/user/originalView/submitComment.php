<?php
include BASE_PATH . '/app/Views/components/layout.php'; ?>

<div>
        <div class="space-between custom-part-with-border mt-5">
            <span><?=__("service") ?> : <?= $doneVisits->service ?></span>
           <span><?=__("service") ?> : <?= $doneVisits->service ?></span>
            <span><?=__("date") ?> : <?= toJalali($doneVisits->visitDatetime)['date']  ?> <?=__("time") ?>: <?= toJalali($doneVisits->visitDatetime)['time']  ?></span>
        </div>
    <ul class="navbar-nav survey-nav flex-fill bg-white w-100 mt-5 rounded" style="margin:0;">
        <li class="nav-item dropdown survey-nav">
            <a href="#"
               data-bs-toggle="collapse"
               data-bs-target="#submenu"
               aria-expanded="false"
               aria-controls="submenu"
               class="justify-content d-flex dropdown-toggle  survey-link nav-link minus ">
                <span class="mx-2">از خدمات ما راضی بودید؟</span>
            </a>
            <ul class="collapse list-unstyled bg-white w-100 rounded px-3" id="submenu">
                <li class="divider-surveys"></li>
                <li class="nav-item">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" style="font-size: large">
                        <label class="form-check-label" for="inlineRadio1">عالی</label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" style="font-size: large">
                        <label class="form-check-label" for="inlineRadio2">خوب</label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" style="font-size: large">
                        <label class="form-check-label" for="inlineRadio3">متوسط</label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option4" style="font-size: large">
                        <label class="form-check-label" for="inlineRadio4">ضعیف</label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="option5" style="font-size: large">
                        <label class="form-check-label" for="inlineRadio5">خیلی ضعیف</label>
                    </div>
                </li>
            </ul>
        </li>
    </ul>

</div>

</div>
</div>