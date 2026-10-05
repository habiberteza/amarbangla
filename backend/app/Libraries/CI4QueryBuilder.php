<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder as LaravelBuilder;


class CI4QueryBuilder
{
    /** @var LaravelBuilder */
    protected $builder;
    protected $sets = [];

    public function __construct(string $table)
    {
        $this->builder = DB::table($table);
    }

    public function getBuilder(): LaravelBuilder
    {
        return $this->builder;
    }

    public function cloneBuilder(): LaravelBuilder
    {
        return clone $this->builder;
    }

    public function select($columns = ['*'])
    {
        if (is_string($columns)) {
            $cols = array_map('trim', explode(',', $columns));
            $this->builder->select($cols);
        } else {
            $this->builder->select($columns);
        }
        return $this;
    }

    public function selectRaw(string $expression, array $bindings = [])
    {
        $this->builder->selectRaw($expression, $bindings);
        return $this;
    }

    public function selectSum(string $select = '', string $alias = '')
    {
        if (empty($alias)) {
            $parts = explode('.', $select);
            $alias = end($parts);
        }
        $this->builder->selectRaw("SUM({$select}) as {$alias}");
        return $this;
    }

    public function selectMax(string $select = '', string $alias = '')
    {
        if (empty($alias)) {
            $parts = explode('.', $select);
            $alias = end($parts);
        }
        $this->builder->selectRaw("MAX({$select}) as {$alias}");
        return $this;
    }

    public function selectMin(string $select = '', string $alias = '')
    {
        if (empty($alias)) {
            $parts = explode('.', $select);
            $alias = end($parts);
        }
        $this->builder->selectRaw("MIN({$select}) as {$alias}");
        return $this;
    }

    public function selectAvg(string $select = '', string $alias = '')
    {
        if (empty($alias)) {
            $parts = explode('.', $select);
            $alias = end($parts);
        }
        $this->builder->selectRaw("AVG({$select}) as {$alias}");
        return $this;
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->sets[$k] = $v;
            }
        } else {
            $this->sets[$key] = $value;
        }
        return $this;
    }

    public function getSets(): array
    {
        return $this->sets;
    }

    public function where($column, $value = null)
    {
        if ($column instanceof \Closure) {
            $this->builder->where(function ($sub) use ($column) {
                $subBuilder = new CI4QueryBuilder('');
                $subBuilder->builder = $sub;
                $column($subBuilder);
            });
            return $this;
        }

        if (is_array($column)) {
            foreach ($column as $k => $v) {
                if ($v === null) {
                    $this->builder->whereNull($k);
                } else {
                    $this->where($k, $v);
                }
            }
            return $this;
        }

        if (is_string($column) && $value === null) {
            if (stripos($column, 'IS NULL') !== false) {
                $col = trim(str_ireplace('IS NULL', '', $column));
                $this->builder->whereNull($col);
            } elseif (stripos($column, 'IS NOT NULL') !== false) {
                $col = trim(str_ireplace('IS NOT NULL', '', $column));
                $this->builder->whereNotNull($col);
            } else {
                $this->builder->whereRaw($column);
            }
            return $this;
        }

        if (is_string($column)) {
            $trimmed = trim($column);
            if (str_ends_with($trimmed, '!=')) {
                $this->builder->where(trim(substr($trimmed, 0, -2)), '!=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<=')) {
                $this->builder->where(trim(substr($trimmed, 0, -2)), '<=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '>=')) {
                $this->builder->where(trim(substr($trimmed, 0, -2)), '>=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<>')) {
                $this->builder->where(trim(substr($trimmed, 0, -2)), '<>', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<')) {
                $this->builder->where(trim(substr($trimmed, 0, -1)), '<', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '>')) {
                $this->builder->where(trim(substr($trimmed, 0, -1)), '>', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '=')) {
                $this->builder->where(trim(substr($trimmed, 0, -1)), '=', $value);
                return $this;
            }
        }

        $this->builder->where($column, $value);
        return $this;
    }

    public function orWhere($column, $value = null)
    {
        if (is_array($column)) {
            foreach ($column as $k => $v) {
                $this->orWhere($k, $v);
            }
            return $this;
        }

        if (is_string($column) && $value === null) {
            $this->builder->orWhereRaw($column);
            return $this;
        }

        if (is_string($column)) {
            $trimmed = trim($column);
            if (str_ends_with($trimmed, '!=')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -2)), '!=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<=')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -2)), '<=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '>=')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -2)), '>=', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<>')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -2)), '<>', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '<')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -1)), '<', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '>')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -1)), '>', $value);
                return $this;
            }
            if (str_ends_with($trimmed, '=')) {
                $this->builder->orWhere(trim(substr($trimmed, 0, -1)), '=', $value);
                return $this;
            }
        }

        $this->builder->orWhere($column, $value);
        return $this;
    }

    public function whereIn(string $column, array $values)
    {
        $this->builder->whereIn($column, $values);
        return $this;
    }

    public function whereNotIn(string $column, array $values)
    {
        $this->builder->whereNotIn($column, $values);
        return $this;
    }

    public function like(string $column, string $match)
    {
        $this->builder->where($column, 'LIKE', "%{$match}%");
        return $this;
    }

    public function orLike(string $column, string $match)
    {
        $this->builder->orWhere($column, 'LIKE', "%{$match}%");
        return $this;
    }

    public function join(string $table, string $first, ?string $operator = null, ?string $second = null, string $type = 'inner')
    {
        if (in_array(strtolower((string) $operator), ['left', 'right', 'inner', 'outer', 'full'], true) && $second === null && strpos($first, '=') !== false) {
            $type = strtolower($operator);
            $operator = null;
        }

        if ($operator === null && strpos($first, '=') !== false) {
            list($left, $right) = explode('=', $first, 2);
            $this->builder->join($table, trim($left), '=', trim($right), $type);
        } else {
            $this->builder->join($table, $first, $operator, $second, $type);
        }
        return $this;
    }

    public function leftJoin(string $table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    public function orderBy(string $column, string $direction = 'ASC')
    {
        $this->builder->orderBy($column, $direction);
        return $this;
    }

    public function groupBy($groups)
    {
        if (is_string($groups)) {
            if (stripos($groups, '(') !== false) {
                // Expression like MONTH(createdDtm),YEAR(createdDtm)
                $this->builder->groupByRaw($groups);
                return $this;
            }
            $groups = array_map('trim', explode(',', $groups));
        }
        $this->builder->groupBy($groups);
        return $this;
    }

    public function limit(int $value, int $offset = 0)
    {
        $this->builder->limit($value);
        if ($offset > 0) {
            $this->builder->offset($offset);
        }
        return $this;
    }

    public function get()
    {
        return new CI4QueryResult($this->builder->get());
    }

    public function countAll(): int
    {
        return $this->builder->count();
    }

    public function countAllResults(): int
    {
        return $this->builder->count();
    }

    public function insert(?array $data = null)
    {
        if ($data === null) {
            $data = $this->sets;
            $this->sets = [];
        }
        return $this->builder->insertGetId($data);
    }

    public function insertBatch(array $data)
    {
        return $this->builder->insert($data);
    }

    public function update(?array $data = null)
    {
        if ($data === null) {
            $data = $this->sets;
            $this->sets = [];
        }
        return $this->builder->update($data);
    }

    public function delete()
    {
        return $this->builder->delete();
    }

    public function truncate()
    {
        return $this->builder->truncate();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->builder, $name], $arguments);
    }
}
