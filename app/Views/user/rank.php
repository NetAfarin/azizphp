
<div class="container-fluid h-100 d-flex justify-content-center align-items-center">
    <div class="w-100" style="max-width: 500px;">
        <form method="post" class="rounded bg-white p-4 text-center shadow">
            <?= csrf_field() ?>
            <h3 class="mb-3 ">نظرسنجی</h3>
            <div class="mx-3">
                 <div class="d-flex justify-content-between align-items-center my-4">
                        <span>کیفیت خدمات:<span class="bullet-color"> *</span></span>
                        <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="service_quality<?=$i?>" name="service_quality" value="<?=$i?>">
                            <label for="service_quality<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                        </div>
                        </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span>کیفیت ابزارهای استفاده شده:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="tools_quality<?=$i?>" name="tools_quality" value="<?=$i?>">
                            <label for="tools_quality<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span>رفتار کارمند:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="employee_behavior<?=$i?>" name="employee_behavior" value="<?=$i?>">
                            <label for="employee_behavior<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <span>رعایت زمانبندی:<span class="bullet-color"> *</span></span>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="time<?=$i?>" name="time" value="<?=$i?>">
                            <label for="time<?=$i?>"><i class="far fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="my-4">
                    <label>چگونه ما را پیدا کردید؟<span class="bullet-color"> *</span></label>
                    <select class="js-example-basic-single form-select w-100 " name="source">
                    <?php foreach ($source as $item):?>
                        <option value="<?= $item->id ?>"><?=($lang == "fa")  ? $item->fa_title : $item->en_title ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="my-4">
                    <label for="exampleFormControlTextarea1" class="form-label">نظرات و پیشنهادات</label>
                    <textarea class="form-control auto-expand"></textarea>
                </div>
                </div>

            <button type="submit" class="btn btn-outline-primary">ارسال</button>
        </form>
    </div>
</div>

</div>
</div>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
        });
        $('.auto-expand').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>