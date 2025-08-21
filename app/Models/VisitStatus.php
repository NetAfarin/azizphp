<?php
namespace App\Models;

use App\Core\Model;

class VisitStatus extends Model
{
    protected string $table = 'visit_status_table';

    protected array $fillable = [
        'id',
        'fa_title',
        'en_title',
        'is_active',
    ];
}
