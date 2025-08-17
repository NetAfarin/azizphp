<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class EmployeeService extends Model
{
    protected string $table = 'employee_service_table';

    protected array $fillable = [
        'id',
        'service_id',
        'user_id',
        'price',
        'free_hour',
        'estimated_duration',
    ];
    protected array $virtual = [
        'en_title',
        'fa_title',
    ];

    public function service(): ?Service
    {
        return Service::find($this->service_id);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = Database::pdo()->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND deleted = 0");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }


}
