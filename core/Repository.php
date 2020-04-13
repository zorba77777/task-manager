<?php

namespace core;

use core\SqlRequestParams;

abstract class Repository
{
    protected ActiveRecord $model;

    public function find(SqlRequestParams $params)
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
}