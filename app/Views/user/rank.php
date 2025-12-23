
<div class="container-fluid h-100 d-flex justify-content-center align-items-center">
    <div class="w-100" style="max-width: 500px;">
        <form method="post" class="rounded bg-white p-4 text-center shadow">
            <?= csrf_field() ?>
            <h3 class="mb-3 "><?= __("survey") ?></h3>
            <div class="d-flex justify-content-between align-items-center">
                <span><?= __("employee") ?>: <?= $survey->employeeName ." ". $survey->employeeLastName ?></span>
                <span><?= __("customer") ?>: <?= $survey->customerFirstName ." ". $survey->customerLastName ?></span>
            </div>
            <div class="d-flex justify-content-sm-between align-items-center">
                <span><?= __("service") ?>: <?= $survey->service ?></span>
                <span><?= __("date") ?>: <?= toJalali($survey->visitDatetime)['date'] ?>/ <?= toJalali($survey->visitDatetime)['time'] ?></span>
            </div>
            <div class="mx-3">
                 <div class="d-flex justify-content-between align-items-center my-4">
                        <span><?= __("service_quality") ?>:<span class="bullet-color"> *</span></span>
                        <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="service_quality<?=$i?>" name="service_quality" value="<?=$i?>">
                            <label for="service_quality<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                            <?php if (!empty($errors['required'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['required'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span><?= __("tools_quality") ?>:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="tools_quality<?=$i?>" name="tools_quality" value="<?=$i?>">
                            <label for="tools_quality<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                        <?php if (!empty($errors['tools_quality'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['tools_quality'][0]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span><?=__("employee_behavior")?>:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id=
                            "employee_behavior<?=$i?>" name="employee_behavior" value="<?=$i?>">
                            <label for="employee_behavior<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                        <?php if (!empty($errors['employee_behavior'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['employee_behavior'][0]) ?></div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span><?=__("on_time")?>:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="on_time<?=$i?>" name="on_time" value="<?=$i?>">
                            <label for="on_time<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                        <?php if (!empty($errors['on_time'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['on_time'][0]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="my-4">
                    <label><?=__("how_find_us")?>؟<span class="bullet-color"> *</span></label>
                    <select class="js-example-basic-single form-select w-100 " name="source">
                    <?php foreach ($source as $item):?>
                        <option value="<?= $item->id ?>"><?=($lang == "fa")  ? $item->fa_title : $item->en_title ?></option>
                        <?php endforeach;?>
                    </select>
                    <?php if (!empty($errors['source'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['source'][0]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="my-4 show-other">
                    <label for="other"><?=__("other")?>:<span class="bullet-color"> *</span></label>
                     <input type="text" class="form-control" id="other" name="other">
                </div>
                <div class="my-4">
                    <label for="exampleFormControlTextarea1" class="form-label"><?=__("feedback")?></label>
                    <textarea class="form-control auto-expand" name="suggestions"></textarea>
                </div>
                </div>

            <button type="submit" class="btn btn-outline-primary"><?=__("submit")?></button>
        </form>
    </div>
</div>

</div>
</div>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
            dropdownParent: $(document.body)
        });
        $('.auto-expand').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        $('.show-other').hide();
        $('.js-example-basic-single').on('change', function() {
            var selectedId = $(this).val();
            var isOtherSelected = (selectedId == 6);
            $('.show-other').toggle(isOtherSelected);
        });
    });
</script>