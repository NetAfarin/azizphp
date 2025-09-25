<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class Salon extends Model
{
    protected string $table = 'salon_table';

    protected array $fillable = [
        'id',
        'username',
        'name',
        'avatar',
        'manager',
        'manager_mobile',
        'manager_email',
        'about_us',
        'link_name',
        'plan_id',
        'postal_address',
        'start_day_of_week',
        'start_time',
        'end_time',
        'latitude',
        'longitude',
        'active_weekend_1',
        'active_weekend_2',
        'active_holidays',
        'start_time_weekend_1',
        'end_time_weekend_1',
        'start_time_weekend_2',
        'end_time_weekend_2',
        'start_time_holidays',
        'end_time_holidays',
        'max_reserve_day',
        'active',
        'deleted',
    ];

}
