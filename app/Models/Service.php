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
    protected array $virtualKeys = [
        'id',
        'parent_title',
        'title',
        'en_title',
        'childCount',
    ];
    public static function parentServices(): array
    {
        return (new static())
            ->where('parent_id', '=', 0)
            ->get();
    }

    public static function servicesByParentId(int $parentId): array
    {
        return (new static())::query()->select(['service_table.*',(APP_LANG === 'fa' ?'service_table.fa_title':'service_table.en_title' ).' AS title'])
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

    public static function getServicesOnly(): Service
    {
        return Service::query()->select(['service_table.id','service_table.parent_id',
            (APP_LANG == "fa" ? "p.fa_title" : "p.en_title")." as parent_title",
            (APP_LANG == "fa" ? "service_table.fa_title" : "service_table.en_title")." as title",            '(SELECT COUNT(*) FROM service_table AS c WHERE c.parent_id = service_table.id AND c.deleted = 0) AS childCount',
        ])->join('service_table AS p', 'service_table.parent_id', '=', 'p.id', 'LEFT')
            ->where("service_table.parent_id", "<>", 0)
            ->where('service_table.deleted', '=', 0);
    }

    public static function getCategoriesOnly(): Service
    {
        return Service::query()->select(['service_table.id', 'service_table.parent_id',
            (APP_LANG == "fa" ? "p.fa_title" : "p.en_title")." as parent_title",
            (APP_LANG == "fa" ? "service_table.fa_title" : "service_table.en_title")." as title",
            '(SELECT COUNT(*) FROM service_table AS c WHERE c.parent_id = service_table.id AND c.deleted = 0) AS childCount',
        ])->join('service_table AS p', 'service_table.parent_id', '=', 'p.id', 'LEFT')
            ->where("service_table.parent_id", "=", 0)
            ->where('service_table.deleted', '=', 0);
    }

    public static function getAll(): Service
    {
        return Service::query()->select([
            'service_table.id',
            'service_table.parent_id',
            'service_table.service_key',
            'service_table.created_at',
            'service_table.updated_at',
            (APP_LANG == "fa" ? "p.fa_title" : "p.en_title")." as parent_title",
            (APP_LANG == "fa" ? "service_table.fa_title" : "service_table.en_title")." as title",
            '(SELECT COUNT(*) FROM service_table AS c WHERE c.parent_id = service_table.id AND c.deleted = 0) AS childCount',
            ])->join('service_table AS p', 'service_table.parent_id', '=', 'p.id', 'LEFT')
            ->where('service_table.deleted', '=', 0);
    }
}
