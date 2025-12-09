<?php
namespace App\Models;
use App\Core\Model;
class EmployeeBookingListTable extends Model
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
        'updated_at',
    ];
    protected array $virtualKeys = [
        'id',
        'title',
        'en_title',
        'fa_title',
        'first_name',
        'last_name',
        'start_day_of_week',
        'off_day',
        'start_time',
        'end_time',
        'durationTitle',
        'duration_id',
    ];
    public static function getEmployeeService($id) : array
    {
        $results = EmployeeBookingListTable::query()
            ->select([
                'ut.id',
                'st.fa_title as fa_title',
                'ut.first_name as first_name',
                'ut.last_name as last_name'
            ])
            ->join('user_table as ut', 'ut.id', '=', 'employee_booking_list_table.user_id')
            ->join('employee_service_table as est', 'est.id', '=', 'employee_booking_list_table.employee_service_id')
            ->join('service_table as st', 'st.id', '=', 'est.service_id')
            ->where('est.service_id', '=', $id)
            ->groupBy('ut.id')
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

