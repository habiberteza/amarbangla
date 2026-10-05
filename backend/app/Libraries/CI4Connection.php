<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class CI4Connection
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function table(string $table): CI4QueryBuilder
    {
        return new CI4QueryBuilder($table);
    }

    public function query(string $sql, array $bindings = [])
    {
        $res = DB::select($sql, $bindings);
        return new CI4QueryResult(collect($res));
    }

    public function getLastQuery()
    {
        return DB::getQueryLog();
    }

    public function transStart(): bool
    {
        try {
            DB::beginTransaction();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function transComplete(): bool
    {
        try {
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            try {
                DB::rollBack();
            } catch (\Throwable $t) {}
            return false;
        }
    }

    public function transBegin(): bool
    {
        return $this->transStart();
    }

    public function transCommit(): bool
    {
        return $this->transComplete();
    }

    public function transRollback(): bool
    {
        try {
            DB::rollBack();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function transStatus(): bool
    {
        return true;
    }

    public function insertID(): int|string
    {
        return DB::getPdo()->lastInsertId();
    }

    public function affectedRows(): int
    {
        return 1;
    }

    public function error(): array
    {
        return ['code' => 0, 'message' => ''];
    }
}
