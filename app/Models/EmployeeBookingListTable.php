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
}

