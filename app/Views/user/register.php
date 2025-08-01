<h1><?= __('register') ?></h1>

<?php //if (!empty($errors)): ?>
<!---->
<!--    <div class="alert alert-danger">-->
<!--        <ul>-->
<!--            --><?php //foreach ($errors as $fieldErrors): ?>
<!--                --><?php //foreach ((array)$fieldErrors as $e): ?>
<!--                    <li>--><?php //= htmlspecialchars($e) ?><!--</li>-->
<!--                --><?php //endforeach; ?>
<!--            --><?php //endforeach; ?>
<!--        </ul>-->
<!--    </div>-->
<?php //endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $key => $fieldErrors): ?>
                <?php if (is_numeric($key)): ?>
                    <li><?= htmlspecialchars($fieldErrors) ?></li> <!-- General error -->
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>



<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register') ?> <?= __('success') ?> ✅
    </div>
<?php endif; ?>

<form method="post" class="row g-3" action="<?= BASE_URL ?>/user/register">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="first_name"><?= __('first_name') ?></label>
        <input type="text" class="form-control" name="first_name" id="first_name" value="<?= old('first_name') ?>">
        <?php if (!empty($errors['first_name'])): ?>
            <div class="text-danger small">
                <?= htmlspecialchars($errors['first_name'][0]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="last_name"><?= __('last_name') ?></label>
        <input type="text" class="form-control" name="last_name" id="last_name" value="<?= old('last_name') ?>">
        <?php if (!empty($errors['last_name'])): ?>
            <div class="text-danger small">
                <?= htmlspecialchars($errors['last_name'][0]) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="phone_number"><?= __('phone_number') ?></label>
        <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?= old('phone_number') ?>">
        <?php if (!empty($errors['phone_number'])): ?>
            <div class="text-danger small">
                <?= htmlspecialchars($errors['phone_number'][0]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password"><?= __('password') ?></label>
        <input type="password" class="form-control" name="password" id="password" value="<?= old('password') ?>">
        <?php if (!empty($errors['password'])): ?>
            <div class="text-danger small">
                <?= htmlspecialchars($errors['password'][0]) ?>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('submit') ?></button>
</form>




