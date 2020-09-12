<?php

namespace core;

use core\helpers\StringHelper;

/**
 * Класс для работы с GET и POST запросами
 *
 * Class Request
 * @package core
 */

class Request
{
    /**
     * Содержит массив данных, полученных из GET или POST запроса
     * @var array
     */
    public array $requestArray = [];

    /**
     * В конструкторе класса заполняется массив запроса
     * Request constructor.
     */
    public function __construct()
    {
        if (!empty($_POST)) {
            $this->requestArray = $_POST;
        } elseif (!empty($_GET)) {
            $this->requestArray = $_GET;
        }
    }

    /**
     * В функции производится очистка получаемого значения GET или POST запроса и возвращается очищенное значение
     * @param $param
     * @return string|null
     */
    public function getParam($param): ?string
    {
        if (isset ($this->requestArray[$param])) {
            return StringHelper::clean($this->requestArray[$param]);
        } else {
            return null;
        }
    }

    /**
     * Функция возвращает имя сервера
     * @return string
     */
    public function getServerName(): string
    {
        return $_SERVER['SERVER_NAME'];
    }
    /**
     * Функция возвращает имя страницы, с которой перешел пользователь
     * @return string
     */
    public function getReferrer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

}