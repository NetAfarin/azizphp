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
    public static function parentServices(): array
    {
        return (new static())
            ->where('parent_id', '=', 0)
            ->get();
    }

    public static function servicesByParentId(int $parentId): array
    {
        return (new static())
            ->where('parent_id', '=', $parentId)
            ->get();
    }
    public static function groupByParentId(): array
    {
        return (new static())
            ->groupBy('parent_id')
            ->get();
    }
    public static function groupedForSelect(): array
    {
        $parents = static::parentServices();
        $result = [];

        foreach ($parents as $parent) {
            $children = static::servicesByParentId($parent->id);
            if (!empty($children)) {
                $result[] = [
                    'parent' => $parent,
                    'children' => $children
                ];
            }
        }

        return $result;
    }
}
