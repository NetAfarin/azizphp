<?php
namespace App\Core;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected string $table;
    protected array $attributes = [];
    protected array $fillable = [];

    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    public function fill(array $data): void
    {
        foreach ($this->fillable as $key) {
            if (isset($data[$key])) {
                $this->attributes[$key] = $data[$key];
            }
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    public static function all(): array
    {
        $instance = new static();
        $stmt = Database::pdo()->query("SELECT * FROM {$instance->table}");
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }

    public static function find(int $id): ?static
    {
        $instance = new static();
        $stmt = Database::pdo()->prepare("SELECT * FROM {$instance->table} WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch() ?: null;
    }

    public static function where(string $column, $value): array
    {
        $instance = new static();
        $stmt = Database::pdo()->prepare("SELECT * FROM {$instance->table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
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
        $result = $stmt->execute($values);

        if ($result) {
            $this->attributes['id'] = Database::pdo()->lastInsertId();
        }

        return $result;
    }

    protected function update(): bool
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", $this->fillable));
        $values = array_map(fn($key) => $this->attributes[$key] ?? null, $this->fillable);
        $values[] = $this->attributes['id'];

        $stmt = Database::pdo()->prepare("UPDATE {$this->table} SET $setClause WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(): bool
    {
        if (!isset($this->attributes['id'])) return false;

        $stmt = Database::pdo()->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }
}
