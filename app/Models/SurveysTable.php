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
}