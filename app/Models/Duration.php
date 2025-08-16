<?php

namespace App\Models;

use App\Core\Model;

class Duration extends Model
{
    protected string $table = 'duration_table';

    protected array $fillable = [
        'id',
        'title',
        'duration',
    ];
}
