<h2 class="mb-4">اطلاعات کاربر</h2>
<ul class="list-group">
    <li class="list-group-item"><strong>نام:</strong> <?= $user->first_name ?></li>
    <li class="list-group-item"><strong>نام خانوادگی:</strong> <?= $user->last_name ?></li>
    <li class="list-group-item"><strong>تاریخ تولد:</strong> <?= $user->birth_date ?></li>
    <li class="list-group-item"><strong>موبایل:</strong> <?= $user->phone_number ?></li>
    <li class="list-group-item"><strong>فعال:</strong> <?= $user->is_active ? 'بله' : 'خیر' ?></li>
</ul>
