<?= flash('success') ?>

    <h2><?= __('dashboard') ?></h2>

<?php if (!empty($sections['dashboard_info'])): ?>
    <p><?= $sections['dashboard_info']['description'] ?></p>
<?php endif; ?>

<?php if (!empty($sections)): ?>
    <ul>
        <?php foreach ($sections as $key => $section): ?>
            <?php if ($key !== 'dashboard_info'): ?>
                <li><a href="<?= $section['url'] ?>"><?= htmlspecialchars($section['title']) ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>