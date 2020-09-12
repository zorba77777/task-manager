<?php
namespace core;
use PDO;

/**
 * Реализуется паттерн Front Controller.
 * Через данный класс проходят все запросы к веб-сайту.
 *
 * Class App
 * @package core
 */
class App
{
    /**
     * Поле хранит экземпляр класса Session для работы с сессиями
     * @var Session
     */
    public static Session $session;

    /**
     * Поле хранит экземпляр класса PDO  для работы с БД
     * @var PDO
     */
    public static PDO $pdo;

    /**
     * Поле хранит экземпляр класса Request для работы с GET и POST запросами
     * @var Request
     */
    public static Request $request;

    /**
     * Хранит название действия, которое должен совершить контроллер
     * @var string
     */
    private string $action;

    /**
     * Хранит экземпляр контроллера
     * @var Controller
     */
    private Controller $controller;

    /**
     * Функция производит настройку базовых параметров приложения для последующего выполнения
     * @param $config
     */
    public function configure($config)
    {
        if (isset($config['dataBaseSettings'])) {
            $this->setDbConnection($config['dataBaseSettings']);
        }

        $this->setSession();
        $this->setRequest();
        $this->setAction($config['startPageParam']['action']);
        $this->setController($config['startPageParam']['controller']);

    }

    /**
     * Функция настраивает сессию
     */
    private function setSession()
    {
        self::$session = new Session();
    }

    /**
     * Функция настраивает соединение с БД в соответствии с переданными конфигурационными параметрами
     * @param $config
     */
    private function setDbConnection($config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbName']};charset={$config['charset']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $config['userName'], $config['password'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        self::$pdo = $pdo;
    }

    /**
     * Функция настраивает GET и POST запросы
     */
    private function setRequest()
    {
        self::$request = new Request();
    }

    /**
     * Функция получает из URI и устанавливает действие для контроллера
     * @param $startAction
     */
    private function setAction($startAction)
    {
        if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index') {
            $this->action = $startAction;
        } else {
            preg_match('/\/(.*)/', $_REQUEST['q'], $matches);
            $this->action = $matches[1];
        }
    }

    /**
     * Функция получает из URI и устанавливает контроллер
     * @param $startController
     */
    private function setController($startController)
    {
        if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index') {
            $this->controller = new $startController($this->action);
        } else {
            preg_match('/\/(.*)\/.*/', $_SERVER['REQUEST_URI'], $matches);
            $controllerName = ucfirst($matches[1]);

            $controllerClassName = 'controllers\\' . $controllerName . 'Controller';

            $this->controller = new $controllerClassName($this->action);
        }
    }

    /**
     * Функция запускает на выполнение действие
     */
    public function run()
    {
        $this->controller->runAction();
    }

}