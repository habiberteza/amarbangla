<?php

namespace App\Models;

use App\Libraries\CI4QueryBuilder;
use Illuminate\Support\Facades\DB;

class CI4Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $allowedFields = [];
    public $pager;

    /** @var CI4QueryBuilder */
    protected $builder;

    public function __construct()
    {
        $this->resetBuilder();
    }

    public function builder(): CI4QueryBuilder
    {
        return $this->builder;
    }

    protected function resetBuilder()
    {
        $this->builder = new CI4QueryBuilder($this->table);
    }

    public function asArray()
    {
        return $this;
    }

    public function asObject()
    {
        return $this;
    }

    public function protect(bool $protect = true)
    {
        return $this;
    }

    public function set($key, $value = null)
    {
        $this->builder->set($key, $value);
        return $this;
    }

    public function where($column, $value = null)
    {
        $this->builder->where($column, $value);
        return $this;
    }

    public function orWhere($column, $value = null)
    {
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
        $this->builder->like($column, $match);
        return $this;
    }

    public function orLike(string $column, string $match)
    {
        $this->builder->orLike($column, $match);
        return $this;
    }

    public function select($columns = ['*'])
    {
        $this->builder->select($columns);
        return $this;
    }

    public function selectRaw(string $expression, array $bindings = [])
    {
        $this->builder->selectRaw($expression, $bindings);
        return $this;
    }

    public function selectSum(string $select = '', string $alias = '')
    {
        $this->builder->selectSum($select, $alias);
        return $this;
    }

    public function selectMax(string $select = '', string $alias = '')
    {
        $this->builder->selectMax($select, $alias);
        return $this;
    }

    public function selectMin(string $select = '', string $alias = '')
    {
        $this->builder->selectMin($select, $alias);
        return $this;
    }

    public function selectAvg(string $select = '', string $alias = '')
    {
        $this->builder->selectAvg($select, $alias);
        return $this;
    }

    public function join(string $table, string $first, string $operator = null, string $second = null, string $type = 'inner')
    {
        $this->builder->join($table, $first, $operator, $second, $type);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC')
    {
        $this->builder->orderBy($column, $direction);
        return $this;
    }

    public function groupBy($groups)
    {
        $this->builder->groupBy($groups);
        return $this;
    }

    public function limit(int $value, int $offset = 0)
    {
        $this->builder->limit($value, $offset);
        return $this;
    }

    public function get()
    {
        $result = $this->builder->get();
        $this->resetBuilder();
        return $result;
    }

    public function findAll(): array
    {
        $result = $this->builder->get()->getResult();
        $this->resetBuilder();
        return $result;
    }

    public function first()
    {
        $result = $this->builder->get()->getRow();
        $this->resetBuilder();
        return $result;
    }

    public function find($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function paginate(int $perPage = null, string $group = 'default', ?int $page = null, int $segment = 0)
    {
        $perPage = $perPage ?: 20;
        $page = $page ?: (int) (request()->input('page_' . $group) ?? request()->input('page') ?? 1);
        if ($page < 1) {
            $page = 1;
        }

        // Count total matching records
        $countQuery = clone $this->builder->getBuilder();
        $total = $countQuery->count();

        $offset = ($page - 1) * $perPage;
        $this->builder->limit($perPage, $offset);

        $results = $this->findAll();

        $this->pager = new \App\Libraries\CI4Pager($total, $perPage, $page, $group);

        return $results;
    }

    public function countAll(): int
    {
        return DB::table($this->table)->count();
    }

    public function countAllResults(): int
    {
        $cnt = $this->builder->countAllResults();
        $this->resetBuilder();
        return $cnt;
    }

    protected $insertID = 0;

    public function insert(?array $data = null)
    {
        if ($data === null) {
            $data = $this->builder->getSets();
        }
        $filtered = $this->filterAllowed($data);
        $res = DB::table($this->table)->insertGetId($filtered);
        $this->insertID = $res;
        $this->resetBuilder();
        return $res;
    }

    public function getInsertID(): int|string
    {
        return $this->insertID ?: DB::getPdo()->lastInsertId();
    }

    public function insertID(): int|string
    {
        return $this->getInsertID();
    }

    public function insertBatch(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function update($id = null, ?array $data = null)
    {
        if ($id === null && $data === null) {
            $res = $this->builder->update();
            $this->resetBuilder();
            return $res;
        }

        if ($data === null && is_array($id)) {
            $res = $this->builder->update($id);
            $this->resetBuilder();
            return $res;
        }

        if ($data === null) {
            // $id was passed as record ID, but data was provided via ->set()
            $filtered = $this->filterAllowed($this->builder->getSets());
            $res = DB::table($this->table)->where($this->primaryKey, $id)->update($filtered);
            $this->resetBuilder();
            return $res;
        }

        $filtered = $this->filterAllowed($data);
        $res = DB::table($this->table)->where($this->primaryKey, $id)->update($filtered);
        $this->resetBuilder();
        return $res;
    }

    public function delete($id = null)
    {
        if ($id !== null) {
            return DB::table($this->table)->where($this->primaryKey, $id)->delete();
        }
        $res = $this->builder->delete();
        $this->resetBuilder();
        return $res;
    }

    public function truncate()
    {
        return DB::table($this->table)->truncate();
    }

    protected function filterAllowed(array $data): array
    {
        if (empty($this->allowedFields)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->allowedFields));
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            $res = call_user_func_array([$this->builder, $name], $arguments);
            if ($res instanceof CI4QueryBuilder) {
                return $this;
            }
            return $res;
        }
        throw new \BadMethodCallException("Method {$name} does not exist on " . get_class($this));
    }
}
