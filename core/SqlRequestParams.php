<?php


namespace core;

/**
 * Класс реализует паттерн Query Object
 * Класс, служащий для формирования запроса к базе данных. Его поля заполняются условиями для поиска в базе.
 * Class SqlRequestParams
 * @package core
 */

class SqlRequestParams
{
    /**
     * В поле хранится where часть запроса к БД
     * @var string
     */
    public string $where = '';

    /**
     * Хранится limit часть запроса к БД
     * @var string
     */
    public string $limit = '';

    /**
     * Хранится offset часть запроса к БД
     * @var string
     */
    public string $offset = '';

    /**
     * Хранится order by часть запроса к БД
     * @var string
     */
    public string $orderBy = '';

    /**
     * Возвращает where часть запроса
     * @return string|null
     */
    public function getWhereStatement(): ?string
    {
        if ($this->where) {
            return " WHERE {$this->where}";
        } else {
            return null;
        }
    }

    /**
     * Возвращает limit часть запроса
     * @return string|null
     */
    public function getLimitStatement(): ?string
    {
        if ($this->limit) {
            return " LIMIT {$this->limit}";
        } else {
            return null;
        }
    }

    /**
     * Возвращает offset часть запроса
     * @return string|null
     */
    public function getOffsetStatement(): ?string
    {
        if ($this->offset) {
            return " OFFSET {$this->offset}";
        } else {
            return null;
        }
    }

    /**
     * Возвращает order by часть запроса
     * @return string|null
     */
    public function getOrderByStatement(): ?string
    {
        if ($this->orderBy) {
            return " ORDER BY {$this->orderBy}";
        } else {
            return null;
        }
    }

}