<?php
namespace App\Models;

use App\Core\Model;

class PaymentStatus extends Model
{
    protected string $table = 'payment_status_table';

    protected array $fillable = [
        'id',
        'code',
        'fa_title',
        'en_title',
        'is_active',
    ];
}
