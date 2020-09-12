<?php

namespace core;

/**
 * Класс является базовым к классам контроллеров, реализуемых в рамках паттерна MVC. Содержит базовые функции контроллера
 * Class Controller
 * @package core
 */

abstract class Controller
{
    /**
     * Поле хранит в себе название действия, которое должен произвести контроллер
     * @var string
     */
    private string $action;

    /**
     * В конструкторе задается значение действия контроллера
     * Controller constructor.
     * @param $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * Запускает действие на выполнение
     */
    public function runAction()
    {
        if (method_exists($this, $this->action)) {
            $this->{$this->action}();
        }
    }

    /**
     * Функция служит для поиска представления и его вывода на экран. Принимает в качестве параметров имя представления
     * и массив параметров, поля которого содержат необходимые данные для отображения представления.
     * @param $viewName
     * @param array $params
     */
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