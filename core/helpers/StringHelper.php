<?php

namespace core\helpers;

/**
 * Класс, содержащий стандартные функции для работы со строками
 *
 * Class StringHelper
 * @package core\helpers
 */

class StringHelper
{
    /**
     * Функция для очистки строки, полученной из небезоспасного источника
     *
     * @param $string
     * @return string
     */
    public static function clean(string $string): string
    {
        $string = strip_tags($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        $string = trim($string);
        return $string;
    }

}

