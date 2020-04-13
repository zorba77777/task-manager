<?php

namespace core;


abstract class ActiveRecord
{
    public static string $tableName;


    public function getTableName()
    {
        return static::$tableName;
    }

    abstract public function getPrimaryKeyName();

    abstract public function getPrimaryKeyValue();


    public function findRecord(SqlRequestParams $params)
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

    public function deleteRecord() {
        $tb = static::$tableName;

        $whereRequestPart = '';
        foreach ($this as $columnName => $columnValue) {
            $whereRequestPart = $whereRequestPart . $columnName . ' = "' . $columnValue . '" AND ';
        }
        $whereRequestPart = trim($whereRequestPart, ' AND ');

        App::$pdo->query("
            DELETE FROM $tb
            WHERE $whereRequestPart
            ");
    }

    public function findRecordByPrimaryKey($id) {
        $sqlParams = new SqlRequestParams();
        $sqlParams->where = "{$this->getPrimaryKeyName()} = '$id'";
        $this->findRecord($sqlParams);
    }

}