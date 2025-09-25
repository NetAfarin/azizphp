<?php

namespace App\Models;

use App\Core\Model;

class Ticket extends Model
{
    protected string $table = 'tickets_table';

    protected array $fillable = [
        'user_id',
        'status_id',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];

}
