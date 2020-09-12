<?php

namespace core;

/**
 * Реализует паттерн проектирования Active Record
 * Класс, содержащий стандартные функции для работы с записями из БД. Для создания класса для работы с БД
 * необходимо отнаследоваться от данного класса. Каждый экземпляр наследника данного класса представляет собой модель,
 * соответствующий одной записи в таблице БД, для реализации паттерна MVC. В классе-наследнике необходимо создать поля,
 * соответствующие аттрибутам таблицы БД, с которой будет работать модель, а также функцию конструктор класса, где
 * принимать в качестве параметров значения этих полей и присваивать эти параметры полям класса.
 *
 * Class ActiveRecord
 * @package core
 */


abstract class ActiveRecord
{
    /**
     * Поле должно содержать название таблицы БД
     * @var string
     */
    public static string $tableName;

    /**
     * Функция возвращает название таблицы БД, с которой работает модель
     * @return string
     */
    public function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * Функция вовзращает имя ключевого поля записи таблицы БД, с которой работает модель
     * @return string
     */
    abstract public function getPrimaryKeyName(): string;

    /**
     * Функция возвращает значение ключеого поля записи таблицы БД, с которой работает модель
     * @return mixed
     */
    abstract public function getPrimaryKeyValue();

    /**
     * Функция ищет запись таблицы БД в соответствии с принятыми параметрами и заполняет модель значениями найденной
     * записи. Вовзращает true, если запись найдена, и false, если найти запись не удалось.
     * @param SqlRequestParams $params
     * @return bool
     */
    public function findRecord(SqlRequestParams $params): bool
    {
        $tb = static::$tableName;

        $query = "SELECT * FROM $tb";

        $query = $query . $params->getWhereStatement();
        $query = $query . $params->getLimitStatement();
        $query = $query . $params->getOffsetStatement();
        $query = $query . ';';

        $searchResult = App::$pdo->query($query)->fetch();

        if ($searchResult) {
            foreach ($this as $attribute => $value) {
                $this->$attribute = $searchResult[$attribute];
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Функция сохраняет в таблице БД полученные данные. В зависимости от принятого параметра либо сохраняет новую запись
     * в таблице, либо обновляет данные в ней.
     * @param bool $isNewRecord
     */
    public function saveRecord($isNewRecord = true)
    {
        $tb = static::$tableName;

        $columnNames = '';
        $columnValues = '';

        if ($isNewRecord == true) {

            foreach ($this as $attribute => $columnValue) {
                $columnNames = $columnNames . ', ' . $attribute;
                $columnValues = $columnValues . '", "' . $columnValue;
            }

            $columnNames = trim($columnNames, ', ');
            $columnValues = trim($columnValues, '", ');
            $columnValues = '"' . $columnValues . '"';

            App::$pdo->query("
            INSERT INTO $tb ($columnNames) VALUES ($columnValues)
            ");

        } else {

            $setRequestPart = '';
            foreach ($this as $columnName => $columnValue) {
                $setRequestPart = $setRequestPart . $columnName . ' = "' . $columnValue . '", ';
            }
            $setRequestPart = trim($setRequestPart, ', ');

            App::$pdo->query("
              UPDATE $tb
                SET $setRequestPart
                WHERE {$this->getPrimaryKeyName()} = '{$this->getPrimaryKeyValue()}'
              ");
        }
    }

    /**
     * Функция удаляет запись из таблицы БД
     */
    public function deleteRecord() {
        $tb = static::$tableName;

        App::$pdo->query("
            DELETE FROM $tb
            WHERE {$this->getPrimaryKeyName()} = '{$this->getPrimaryKeyValue()}'
            ");
    }

    /**
     * Функция ищет запись в БД по первичному ключу
     */
    public function findRecordByPrimaryKey($id) {
        $sqlParams = new SqlRequestParams();
        $sqlParams->where = "{$this->getPrimaryKeyName()} = '$id'";
        $this->findRecord($sqlParams);
    }

}