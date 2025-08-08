<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class Service extends Model
{
    protected string $table = 'service_table';

    protected array $fillable = [
        'id',
        'service_key',
        'fa_title',
        'en_title',
        'parent_id',
        'created_at',
        'updated_at',
        'deleted',
    ];

    public static function findById(int $id): ?Service
    {
        $instance = new static();
        $stmt = Database::pdo()->prepare("SELECT * FROM {$instance->table} WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch() ?: null;
    }

    public static function all(): array
    {
        $instance = new static();
        $stmt = Database::pdo()->query("SELECT * FROM {$instance->table} WHERE deleted = 0");
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }
}
