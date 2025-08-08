<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class User extends Model
{
    // نام جدول در دیتابیس
    protected string $table = 'user_table';

    // ستون‌هایی که مجاز به fill شدن هستند

    protected array $fillable = [
        'id',
        'first_name',
        'last_name',
        'birth_date',
        'phone_number',
        'register_datetime',
        'password',
        'user_type',
        'is_active',
        'deleted'
    ];


    public static function findByMobile(string $mobile): ?User
    {
        $instance = new static();
        $stmt = Database::pdo()->prepare("SELECT * FROM {$instance->table} WHERE mobile = ?");
        $stmt->execute([$mobile]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch() ?: null;
    }
    public function getUserType(): ?UserType
    {
        return UserType::find($this->user_type);
    }

    public function getRoleTitle(): string
    {
        $type = $this->getUserType();
        return $type->title ?? __('unknown');
    }

}
