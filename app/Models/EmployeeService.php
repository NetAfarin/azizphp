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
        'update_time',
        'estimated_duration',
        'deleted',
    ];
    protected array $virtualKeys = [
        'id',
        'title',
        'en_title',
        'fa_title',
        'first_name',
        'last_name',
    ];
    public static function getEmployeeService($id) : array
    {
        $results = EmployeeService::query()
            ->select([
                'ut.id',
                'st.fa_title as fa_title',
                'ut.first_name as first_name',
                'ut.last_name as last_name'
            ])
            ->join('service_table as st', 'st.id', '=', 'employee_service_table.service_id')
            ->join('user_table as ut', 'ut.id', '=', 'employee_service_table.user_id')
            ->where('employee_service_table.service_id', '=', $id)
            ->get();
        return array_map(function($item) {
            return [
                'id'   => $item->id,
                'fa_title'   => $item->fa_title,
                'first_name' => $item->first_name,
                'last_name'  => $item->last_name
            ];
        }, $results);
    }



}
