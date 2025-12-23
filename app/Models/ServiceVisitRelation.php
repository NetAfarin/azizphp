<?php

namespace App\Models;

use App\Core\Model;

class ServiceVisitRelation extends Model
{
    protected string $table = 'service_visit_relation_table';

    protected array $fillable = [
        'id',
        'visit_id',
        'service_id',
        'price',
        'initial_payment',
        'payment_status',
        'visit_status',
        'employee_id',
        'deleted',
    ];
    protected array $virtualKeys = [
        'customerName',
        'customerLastName',
        'employeeFirstName',
        'employeeLastName',
        'service',
        'visitStatus',
        'visitStatusId',
        'visitDate',
        'registerDatetime',
        'visitDatetime',
        'employeeId',
    ];

    public function visit()
    {
        return $this->belongsTo(Booking::class, 'visit_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status');
    }
    public function visitStatus()
    {
        return $this->belongsTo(VisitStatus::class, 'visit_status');
    }
    public static function visitsDetails(): ServiceVisitRelation
    {
        return ServiceVisitRelation::query()
            ->select([
                "service_visit_relation_table.id as id",
                "ut.first_name AS employeeFirstName",
                "ut.id AS employeeId",
                "ut.last_name AS employeeLastName",
                (APP_LANG == "fa" ? "service_table.fa_title" : "service_table.en_title")." as service",
                (APP_LANG == "fa" ? "vst.fa_title" : "vst.en_title")." as visitStatus",
                "vt.visit_datetime AS visitDate",
                "vt.visit_datetime",
                "vt.register_datetime as registerDatetime",
                "vst.id AS visitStatusId",
                "c.first_name AS customerName",
                "c.last_name AS customerLastName",
            ])
            ->join("visit_table AS vt", "vt.id", "=", "service_visit_relation_table.visit_id")
            ->join("user_table AS ut", "ut.id", "=", "service_visit_relation_table.employee_id")
            ->join("user_table AS c", "c.id", "=", "vt.customer_id")
            ->join("service_table", "service_table.id", "=", "service_visit_relation_table.service_id")
            ->join("visit_status_table AS vst", "vst.id", "=", "service_visit_relation_table.visit_status")
            ->where('vt.deleted', "=", "0");
    }
    public static function visitsDetailsWithStatusType($type)
    {
        return ServiceVisitRelation::query()
            ->select([
                "service_visit_relation_table.id as id",
                "ut.first_name AS employeeFirstName",
                "ut.id AS employeeId",
                "ut.last_name AS employeeLastName",
                (APP_LANG == "fa" ? "service_table.fa_title" : "service_table.en_title")." as service",
                (APP_LANG == "fa" ? "vst.fa_title" : "vst.en_title")." as visitStatus",
                "vt.visit_datetime AS visitDate",
                "vt.register_datetime as registerDatetime",
                "vst.id AS visitStatusId",
                "c.first_name AS customerName",
                "c.last_name AS customerLastName",
            ])
            ->join("visit_table AS vt", "vt.id", "=", "service_visit_relation_table.visit_id")
            ->join("user_table AS ut", "ut.id", "=", "service_visit_relation_table.employee_id")
            ->join("user_table AS c", "c.id", "=", "vt.customer_id")
            ->join("service_table", "service_table.id", "=", "service_visit_relation_table.service_id")
            ->join("visit_status_table AS vst", "vst.id", "=", "service_visit_relation_table.visit_status")
            ->where('service_visit_relation_table.visit_status', "=",$type)
            ->where('vt.deleted', "=", "0");
    }
    public static function getVisitsNumberToday()
    {
        return ServiceVisitRelation::query()
            ->select(["DATE_FORMAT(visit_table.visit_datetime, '%Y-%m-%d') as visitDatetime"])
            ->join("visit_table", "visit_table.id", "=", "service_visit_relation_table.visit_id")
            ->where("DATE_FORMAT(visit_table.visit_datetime, '%Y-%m-%d')" , "=", date("Y-m-d"))
            ->where('visit_table.deleted', "=", "0")
            ->get();

    }
}
