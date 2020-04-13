<?php


namespace core;


class SqlRequestParams
{
    public string $where = '';

    public string $limit = '';

    public string $offset = '';

    public string $orderBy = '';

    public function getWhereStatement()
    {
        if ($this->where) {
            return " WHERE {$this->where}";
        } else {
            return null;
        }
    }

    public function getLimitStatement()
    {
        if ($this->limit) {
            return " LIMIT {$this->limit}";
        } else {
            return null;
        }
    }

    public function getOffsetStatement()
    {
        if ($this->offset) {
            return " OFFSET {$this->offset}";
        } else {
            return null;
        }
    }

    public function getOrderByStatement()
    {
        if ($this->orderBy) {
            return " ORDER BY {$this->orderBy}";
        } else {
            return null;
        }
    }

}