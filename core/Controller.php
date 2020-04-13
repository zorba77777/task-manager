<?php

namespace core;

abstract class Controller
{
    private string $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function runAction()
    {
        if (method_exists($this, $this->action)) {
            $this->{$this->action}();
        }
    }

    public function render($viewName, $params = [])
    {
        $className = strtolower(get_class($this));
        preg_match('/\\\(.*)controller/', $className, $matches);

        $pathToView = __DIR__ . '/../views/' . $matches[1] . '/' . $viewName . '.php';

        foreach ($params as $key => $value) {
            $$key = $value;
        }
        include $pathToView;
    }
}