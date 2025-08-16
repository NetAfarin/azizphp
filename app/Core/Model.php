<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected string $table;
    protected array $attributes = [];
    protected array $fillable = [];
    protected array $wheres = [];
    protected array $joins = [];
    protected ?string $groupBy = null;
    protected ?string $orderBy = null;
    protected ?int $limitCount = null;
    protected ?int $offset = null;
    protected ?string $selectArr = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public static function query(): static
    {
        return new static();
    }

    public function beginTransaction(): bool
    {
        return Database::pdo()->beginTransaction();
    }

    public function commit(): bool
    {
        return Database::pdo()->commit();
    }

    public function rollBack(): bool
    {
        return Database::pdo()->rollBack();
    }

    public function fill(array $data): void
    {
        foreach ($this->fillable as $key) {
            if (isset($data[$key])) {
                $this->attributes[$key] = $data[$key];
            }
        }
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function select(array $columns): static
    {
        $this->selectArr = implode(', ', $columns);
        return $this;
    }

    public static function all(): array
    {
        return static::query()->get();
    }

    public static function find(int $id): ?static
    {
        return static::query()->where('id', '=', $id)->first();
    }

    public function save(): bool
    {
        if (isset($this->attributes['id'])) {
            return $this->update();
        }
        $columns = implode(',', $this->fillable);
        $placeholders = implode(',', array_fill(0, count($this->fillable), '?'));
        $values = array_map(fn($key) => $this->attributes[$key] ?? null, $this->fillable);
        $stmt = Database::pdo()->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $success = $stmt->execute($values);

        if ($success) {
            $this->attributes['id'] = Database::pdo()->lastInsertId();
        }
        return $success;
    }

    protected function update(): bool
    {
        $setParts = [];
        $values = [];

        foreach ($this->fillable as $col) {
            if (array_key_exists($col, $this->attributes)) {
                $setParts[] = "$col = ?";
                $values[] = $this->attributes[$col];
            }
        }

        $values[] = $this->attributes['id'];
        $set = implode(', ', $setParts);

        $stmt = Database::pdo()->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(): bool
    {
        if (!isset($this->attributes['id'])) return false;
        if (in_array('deleted', $this->fillable)) {
            $this->attributes['deleted'] = 1;
            return $this->save();
        }
        $stmt = Database::pdo()->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    public function where(string $column, string $operator, $value): static
    {
        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $this->wheres[] = ['column' => $column, 'operator' => 'IN', 'value' => $values];
        return $this;
    }

    public function whereLike(string $column, string $value): static
    {
        $this->wheres[] = ['column' => $column, 'operator' => 'LIKE', 'value' => "%$value%"];
        return $this;
    }


    public function whereNotLike(string $column, string $value): static
    {
        $this->wheres[] = ['column' => $column, 'operator' => 'NOT LIKE', 'value' => "%$value%"];
        return $this;
    }
    public function orWhere(string $column, string $operator, $value): static
    {
        $this->wheres[] = ['type' => 'OR', 'column' => $column, 'operator' => $operator, 'value' => $value];
        return $this;
    }

    public function groupBy(string $column): static
    {
        $this->groupBy = $column;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            $direction = 'ASC';
        }
        $this->orderBy = "$column $direction";
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limitCount = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    protected function buildWhereClauseAndParams(): array
    {
        $params = [];
        if (empty($this->wheres)) {
            return ['', $params];
        }
        $parts = [];
        foreach ($this->wheres as $w) {
            $type = $w['type'] ?? 'AND';
            $clause = "{$w['column']} {$w['operator']} ?";
            if (!empty($parts)) {
                $parts[] = $type . ' ' . $clause;
            } else {
                $parts[] = $clause;
            }
            $params[] = $w['value'];
        }
        return [' WHERE ' . implode(' ', $parts), $params];
    }
    protected function buildSelectSql(array &$params): string
    {
        [$whereClause, $whereParams] = $this->buildWhereClauseAndParams();
        $params = array_merge($params, $whereParams);

        $sql = "SELECT " . ($this->selectArr ?? '*') . " FROM {$this->table}";
        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        $sql .= $whereClause;

        if ($this->groupBy) {
            $sql .= " GROUP BY {$this->groupBy}";
        }
        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }
        if ($this->limitCount !== null) {
            $sql .= " LIMIT ?";
            $params[] = (int)$this->limitCount;
            if ($this->offset !== null) {
                $sql .= " OFFSET ?";
                $params[] = (int)$this->offset;
            }
        }
        return $sql;
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): static
    {
        $this->joins[] = strtoupper($type) . " JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }
    protected function buildCountSql(array &$params): string
    {
        [$whereClause, $whereParams] = $this->buildWhereClauseAndParams();
        $params = array_merge($params, $whereParams);
        if ($this->groupBy) {
            return "SELECT COUNT(*) AS aggregate FROM (SELECT 1 FROM {$this->table}{$whereClause} GROUP BY {$this->groupBy}) AS sub";
        }
        return "SELECT COUNT(*) AS aggregate FROM {$this->table}{$whereClause}";
    }

    public function get(): array
    {
        $params = [];
        $sql = $this->buildSelectSql($params);
        $stmt = Database::pdo()->prepare($sql);

        $i = 1;
        foreach ($params as $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($i++, $val, $type);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->wheres = [];
        $this->joins = [];
        $this->selectArr = null;
        $this->groupBy = null;
        $this->orderBy = null;
        $this->limitCount = null;
        $this->offset = null;

        return array_map(fn($row) => new static($row), $rows);
    }

    public function first(): ?static
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $countParams = [];
        $countSql = $this->buildCountSql($countParams);
        $countStmt = Database::pdo()->prepare($countSql);

        $i = 1;
        foreach ($countParams as $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $countStmt->bindValue($i++, $val, $type);
        }
        $countStmt->execute();
        $total = (int)($countStmt->fetch(PDO::FETCH_ASSOC)['aggregate'] ?? 0);

        $lastPage = max(1, (int)ceil($total / $perPage));
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $this->limit($perPage)->offset(($page - 1) * $perPage);

        $data = $this->get();

        $from = $total === 0 ? 0 : (($page - 1) * $perPage) + 1;
        $to = $total === 0 ? 0 : $from + count($data) - 1;

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'from' => $from,
            'to' => $to,
        ];
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
    public function deleteWhere(array $conditions): bool
    {
        $clauses = [];
        $params = [];

        foreach ($conditions as $col => $val) {
            if (is_array($val)) {
                $placeholders = implode(',', array_fill(0, count($val), '?'));
                $clauses[] = "$col IN ($placeholders)";
                $params = array_merge($params, $val);
            } else {
                $clauses[] = "$col = ?";
                $params[] = $val;
            }
        }

        $where = implode(' AND ', $clauses);
        $sql = "DELETE FROM {$this->table} WHERE $where";

        $stmt = Database::pdo()->prepare($sql);
        return $stmt->execute($params);
    }
}
