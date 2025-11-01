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
        'salon_id',
        'postal_address',
        'national_code',
        'has_launch_time',
        'launch_time',
        'has_dinner_time',
        'dinner_time',
        'follow_shift_from_salon',
        'user_type',
        'is_active',
        'deleted'
    ];
    protected array $virtualKeys = [
        'services',
        'services_name',
        'user_type',
        'result',
        'role_id',
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
    public function isSuperAdmin(): bool
    {
        return $this->user_type === UserType::SUPER_ADMIN;
    }
    public function isSupport(): bool
    {
        return $this->user_type === UserType::SUPPORT;
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
            ->select(['employee_service_table.*','service_table.fa_title','service_table.en_title'])
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
                if ($currentMap[$sid]->estimated_duration != $durationId) {
                    $currentMap[$sid]->estimated_duration = $durationId;
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

    public static function getAllUserWithDetails($column): User
    {
        return User::query()
            ->select([
                (APP_LANG === 'fa' ? 'utt.title' : 'utt.en_title').' AS user_type',
                'user_table.first_name',
                'user_table.id',
                'user_table.'.$column,
                'user_table.last_name',
                'user_table.phone_number',
                'abbas.result AS result',
                's.services AS services_name'
            ])
            ->join('user_type_table as utt', 'user_table.user_type', '=', 'utt.id')
            ->join("(
        SELECT 
            employee_id,
            (6 - (
                (AVG(quality_score_id) +
                 AVG(behavior_score) +
                 AVG(onTime_score) +
                 AVG(tools_score)) / 4
            )) AS result
        FROM surveys_table
        GROUP BY employee_id
    ) AS abbas", 'abbas.employee_id', '=', 'user_table.id', 'LEFT')
            ->join("(
        SELECT est.user_id, GROUP_CONCAT(st.fa_title SEPARATOR ', ') AS services
        FROM employee_service_table est
        JOIN service_table st ON st.id = est.service_id
        GROUP BY est.user_id
    ) AS s", 's.user_id', '=', 'user_table.id', 'LEFT')
            ->where("user_table.deleted" , "=" , "0");
    }
    public static function getSomeUserWithDetails($userType  , $column): User
    {
        return User::query()
            ->select([
                'utt.title AS user_type',
                'user_table.first_name',
                'user_table.id',
                'user_table.last_name',
                'user_table.'.$column,
                'user_table.phone_number',
                'abbas.result AS result',
                's.services AS services_name'
            ])
            ->join('user_type_table as utt', 'user_table.user_type', '=', 'utt.id')
            ->join("(
        SELECT 
            employee_id,
            (6 - (
                (AVG(quality_score_id) +
                 AVG(behavior_score) +
                 AVG(onTime_score) +
                 AVG(tools_score)) / 4
            )) AS result
        FROM surveys_table
        GROUP BY employee_id
    ) AS abbas", 'abbas.employee_id', '=', 'user_table.id', 'LEFT')
            ->join("(
        SELECT est.user_id, GROUP_CONCAT(st.fa_title SEPARATOR ', ') AS services
        FROM employee_service_table est
        JOIN service_table st ON st.id = est.service_id
        GROUP BY est.user_id
    ) AS s", 's.user_id', '=', 'user_table.id', 'LEFT')->where('user_table.user_type', '=', $userType)->where("user_table.deleted" , "=" , "0");
    }
}
