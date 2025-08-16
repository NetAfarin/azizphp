<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'user_table';


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
        return EmployeeService::query()
            ->join('service_table', 'service_table.id', '=', 'employee_service_table.service_id')
            ->where('employee_service_table.user_id', '=', $this->id)
            ->get();
    }
    public function syncEmployeeServicesWithDetails(array $newServiceIds, array $prices, array $durations): void
    {
        $userId = $this->id;

        $current = EmployeeService::query()
            ->where('user_id', '=', $userId)
            ->get();

        $currentMap = [];
        foreach ($current as $es) {
            $currentMap[$es->service_id] = $es;
        }

        // حذف سرویس‌های غیر انتخابی
        foreach ($currentMap as $sid => $es) {
            if (!in_array($sid, $newServiceIds)) {
                $es->delete();
            }
        }

        foreach ($newServiceIds as $sid) {
            $price = $prices[$sid] ?? null;
            $durationId = $durations[$sid] ?? null;

            if (isset($currentMap[$sid])) {
                $changed = false;
                if ($currentMap[$sid]->price != $price) {
                    $currentMap[$sid]->price = $price;
                    $changed = true;
                }
                if ($currentMap[$sid]->estimated_duration_id != $durationId) {
                    $currentMap[$sid]->estimated_duration_id = $durationId;
                    $changed = true;
                }
                if ($changed) {
                    $currentMap[$sid]->save();
                }
            } else {
                $es = new EmployeeService([
                    'user_id' => $userId,
                    'service_id' => $sid,
                    'price' => $price,
                    'estimated_duration' => $durationId,
                ]);
                $es->save();
            }
        }
    }



}
