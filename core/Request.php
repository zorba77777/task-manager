<?php

namespace core;

use core\helpers\StringHelper;

class Request
{
    public array $requestArray = [];


    public function __construct()
    {
        if (!empty($_POST)) {
            $this->requestArray = $_POST;
        } elseif (!empty($_GET)) {
            $this->requestArray = $_GET;
        }
    }

    public function getParam($param)
    {
        if (isset ($this->requestArray[$param])) {
            return StringHelper::clean($this->requestArray[$param]);
        } else {
            return null;
        }
    }

    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function getReferrer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

}