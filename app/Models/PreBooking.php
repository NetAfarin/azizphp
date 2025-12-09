<?php

namespace App\Models;

use App\Core\Model;

class PreBooking extends Model
{
    protected string $table = 'employee_booking_list_table';

    protected array $fillable = [
        'id',
        'user_id',
        'employee_service_id',
        'time',
        'date',
        'status',
        'created_at',
        'updated_at'
    ];
    protected array $virtualKeys = [
        'service_id',
        'fa_title',
        'en_title'
    ];
    public static function getEmployeeDate($id , $serviceId):array
    {
        $results =  PreBooking::query()
            ->select(['*'])
            ->join('employee_service_table as est', 'est.id', '=', 'employee_booking_list_table.employee_service_id')
            ->where('employee_booking_list_table.user_id', '=' ,  $id)
            ->where('est.service_id', '=' ,  $serviceId)
            ->orderBy('date', 'ASC')
            ->groupBy('date')
            ->get();
        return array_map(function($item) {
            return [
                'id'   => $item->date,
                'date' => toJalali($item->date)['date'],
                'status' => $item->status,
            ];
        }, $results);
    }
    public static function getEmployeeTime($date):array
    {
        $results =  PreBooking::query()
            ->select(['*'])
            ->where('employee_booking_list_table.date', '=' ,  $date)
            ->get();
        return array_map(function($item) {
            return [
                'id'   => $item->id,
                'time'   => $item->time,
                'status' => $item->status,
            ];
        }, $results);
    }
}