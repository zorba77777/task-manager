<?php

namespace core;

use core\SqlRequestParams;

/**
 * Класс реализует паттерн Repository.
 * Его наследники позволяют производить поиск сразу нескольких моделей по заданным критериям. Каждый наследник должен
 * соответствовать одной таблице в БД.
 *
 * Class Repository
 * @package core
 */
abstract class Repository
{
    /**
     * Содержит модель, которая будет использована для поиска записей в в БД.
     * @var ActiveRecord
     */
    protected ActiveRecord $model;

    /**
     * Функция ищет и возвращает массив моделей заданным параметрам.
     * @param \core\SqlRequestParams $params
     * @return array
     */
    public function find(SqlRequestParams $params): array
    {
        $query = "SELECT * FROM {$this->model->getTableName()}";

        $query = $query . $params->getWhereStatement();
        $query = $query . $params->getOrderByStatement();
        $query = $query . $params->getLimitStatement();
        $query = $query . $params->getOffsetStatement();

        $query = $query . ';';

        $searchResult = App::$pdo->query($query)->fetchAll();

        if ($searchResult) {
            $arrayOfModels = [];
            foreach ($searchResult as $key => $attributes) {

                $arrayOfModels[$key] = clone $this->model;

                foreach ($arrayOfModels[$key] as $attrName => $attrValue) {
                    $arrayOfModels[$key]->$attrName = $attributes[$attrName];
                }
            }
            return $arrayOfModels;
        } else {
            return [];
        }
    }

    public function findAll()
    {
        return $this->find(new SqlRequestParams());
    }
}