<?php

namespace App\Models;
use App\Core\Database;
use App\Core\Model;
use PDO;

class UserType extends Model
{
    protected string $table = 'user_type_table';

    protected array $fillable = ['id','title', 'en_title'];

    const EMPLOYEE = 1;
    const CUSTOMER = 2;
    const OPERATOR = 3;
    const ADMIN = 4;


    public static function findByEnTitle(string $enTitle): ?self
    {
        $instance = new static();
        $stmt = Database::pdo()->prepare("SELECT * FROM {$instance->table} WHERE en_title = ?");
        $stmt->execute([$enTitle]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch() ?: null;
    }

}