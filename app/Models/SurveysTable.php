<?php

namespace App\Models;

use App\Core\Model;

class SurveysTable extends Model
{
    protected string $table = 'surveys_table';

    protected array $fillable = [
        'id',
        'service_visit_relation_id',
        'salon_id',
        'quality_score_id',
        'behavior_score',
        'onTime_score',
        'tools_score',
        'feedback_text',
        'survey_datetime',
        'employee_id',
        'register_datetime',
        'link',
        'submitted',
        'submit_datetime',
        'awareness_source_id',
        'awareness_source_text',
        'deleted'
    ];
    protected array $virtualKeys = [
        'employeeName',
        'employeeLastName',
        'customerFirstName',
        'customerLastName',
        'service',
        'visitDatetime',
    ];

    /**
     * @param $link
     * @return SurveysTable
     */
    public static function getSurveyData($link): SurveysTable
    {
        return SurveysTable::query()->select(["st.fa_title as service, vt.visit_datetime as visitDatetime , employee.first_name as employeeName , employee.last_name as employeeLastName , customer.first_name as customerFirstName , customer.last_name as customerLastName , surveys_table.* "])
            ->join("service_visit_relation_table  as svrt", "svrt.id", "=", "surveys_table.service_visit_relation_id")
            ->join("service_table  as st", "st.id", "=", "svrt.service_id")
            ->join("visit_table  as vt", "vt.id", "=", "svrt.visit_id")
            ->join("user_table as employee", "employee.id", "=", "svrt.employee_id")
            ->join("user_table as customer", "customer.id", "=", "vt.customer_id")
            ->where("link", "=", $link)->first();
    }
    public static function checkLink($link) : bool
    {
        $data =  SurveysTable::query()->where("link" , "=" , $link)->first();
        if(empty($data)){
            return false;
        }else{
            return true;
        }
    }
}