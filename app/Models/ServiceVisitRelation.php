<?php

namespace App\Models;

use App\Core\Model;

class ServiceVisitRelation extends Model
{
    protected string $table = 'service_visit_relation_table';

    protected array $fillable = [
        'visit_id',
        'service_id',
        'price',
        'initial_payment',
        'payment_status',
        'visit_status',
        'employee_id',
        'deleted',
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
}
