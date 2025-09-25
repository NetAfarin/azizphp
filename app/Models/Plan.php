<?php

namespace App\Models;

use App\Core\Model;

class Plan extends Model
{
    protected string $table = 'plans_table';

    protected array $fillable = [
        'id',
        'title',
        'description',
        'price',
        'is_active',
        'created_at',
        'updated_at	',
    ];
}
