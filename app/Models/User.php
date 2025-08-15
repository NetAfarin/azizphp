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


    public static function findByPhone(string $phone): ?self
    {
        return (new static())
            ->where('phone_number', '=', $phone)
            ->first();
    }
    public function getUserType(): ?UserType
    {
        return UserType::find($this->user_type);
    }
    public function isAdmin(): bool
    {
        return $this->user_type === UserType::ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->user_type === UserType::OPERATOR;
    }

    public function getRoleTitle(): string
    {
        $type = $this->getUserType();
        return $type->title ?? __('unknown');
    }
    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    public function getEmployeeServices(): array
    {
        return EmployeeService::where('user_id', $this->id)->get();
    }
}
