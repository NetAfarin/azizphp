<?php

namespace App\Models;

use App\Core\Model;
class Booking extends Model
{
    protected string $table = 'visit_table';
    protected array $fillable = [
        'id',
        'user_id',
        'employee_id',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'created_at',
        'updated_at'
    ];
    public function relations()
    {
        return $this->hasMany(ServiceVisitRelation::class, 'visit_id');
    }
    //in method array return mikone
//    public function servicesWithStatus(): array
//    {
//        $relations = $this->relations();
//        $result = [];
//
//        foreach ($relations as $relation) {
//            $result[] = [
//                'service_id'       => $relation->service_id,
//                'service'          => $relation->service()->fa_title ?? null,
//                'employee'         => $relation->employee()->first_name ?? null,
//                'price'            => $relation->price,
//                'initial_payment'  => $relation->initial_payment,
//                'payment_status'   => $relation->paymentStatus()->fa_title ?? null,
//                'visit_status'     => $relation->visitStatus()->fa_title ?? null,
//            ];
//        }
//
//        return $result;
//    }

//in method array of object return mikone
    public function servicesWithStatus(): array
    {
        $relations = $this->relations();

        foreach ($relations as $relation) {
            $relation->service       = $relation->service();
            $relation->employee      = $relation->employee();
            $relation->paymentStatus = $relation->paymentStatus();
            $relation->visitStatus   = $relation->visitStatus();
        }

        return $relations;
    }

}