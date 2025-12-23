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
    const BOOKED = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;
    const IN_PROGRESS = 4;
    const COMPLETED = 5;
    const NO_SHOW = 6;
    const POSTPONED = 7;
}
