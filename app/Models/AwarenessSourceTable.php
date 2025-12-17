<?php

namespace App\Models;

use App\Core\Model;
class AwarenessSourceTable extends Model
{
    protected string $table = 'awareness_source_table';
    protected array $fillable = [
        'id',
        'fa_title',
        'en_title',
        'is_active',
    ];
}