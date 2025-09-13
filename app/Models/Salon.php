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
        'plan_id',
        'postal_address',
        'latitude',
        'longitude',
        'active',
        'deleted',
    ];

}
