<?php

namespace core\helpers;


class StringHelper
{
    public static function clean($string)
    {
        $string = strip_tags($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        $string = trim($string);
        return $string;
    }

}

