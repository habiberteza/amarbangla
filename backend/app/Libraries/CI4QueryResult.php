<?php

namespace App\Libraries;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class CI4QueryResult implements Countable, IteratorAggregate
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection instanceof \Illuminate\Support\Collection ? $collection : collect($collection);
    }

    public function getRow($n = 0)
    {
        if ($n === 0) {
            return $this->collection->first() ?: null;
        }
        return $this->collection->values()->get($n) ?: null;
    }

    public function getRowArray($n = 0)
    {
        $row = $this->getRow($n);
        return $row ? (array) $row : null;
    }

    public function getFirstRow()
    {
        return $this->collection->first() ?: null;
    }

    public function getLastRow()
    {
        return $this->collection->last() ?: null;
    }

    public function getResult()
    {
        return $this->collection->all();
    }

    public function getResultArray()
    {
        return $this->collection->map(function ($item) {
            return (array) $item;
        })->all();
    }

    public function getNumRows(): int
    {
        return $this->collection->count();
    }

    public function num_rows(): int
    {
        return $this->getNumRows();
    }

    public function count(): int
    {
        return $this->getNumRows();
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->getResult());
    }

    public function getFieldCount(): int
    {
        $first = $this->collection->first();
        return $first ? count((array) $first) : 0;
    }

    public function getFieldNames(): array
    {
        $first = $this->collection->first();
        return $first ? array_keys((array) $first) : [];
    }
}
