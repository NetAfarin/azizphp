<?php

namespace App\Models;

use App\Core\Model;

class Holiday extends Model
{
    protected string $table = 'holidays_table';

    protected array $fillable = [
        'id',
        'day',
        'day_shamsi',
        'description',
    ];
}