<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class EmployeeService extends Model
{
    protected string $table = 'employee_service_table';

    protected array $fillable = [
        'id',
        'service_id',
        'user_id',
        'price',
        'estimated_duration',
    ];
    protected array $virtualKeys = [
        'title',
        'en_title',
        'fa_title',
    ];

}
