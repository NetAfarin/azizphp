<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class EmployeeTable extends Model
{
    protected string $table = 'employee_table';
    protected array $fillable = [
        'id',
        'user_id',
        'start_day_of_week',
        'off_day',
        'start_time',
        'end_time',
    ];

    public static function getEmployeeTime($id): array
    {
        $results =  EmployeeTable::query()
            ->select(['*'])
            ->where('employee_table.user_id', '=' ,  $id)
              ->get();
              return array_map(function($item) {
            return [
                'id'   => $item->id,
                'start_time'   => $item->start_time,
                'end_time' => $item->end_time,
            ];
        }, $results);
    }

}
