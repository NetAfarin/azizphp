<?php
namespace App\Models;

use App\Core\Model;

class VisitTable extends Model
{
    protected string $table = 'visit_table';

    protected array $fillable = [
        'id',
        'registrant_user_id',
        'customer_id ',
        'salon_id',
        'visit_datetime',
        'register_datetime',
        'note',
        'deleted',
    ];
}
