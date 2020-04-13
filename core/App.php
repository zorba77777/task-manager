<?php
namespace core;
use PDO;

class App
{
    public static Session $session;

    public static PDO $pdo;

    public static Request $request;

    public static IdentityInterface $identity;

    private string $action;

    private Controller $controller;

    public function configure($config)
    {
        if (isset($config['identityClass'])) {
            $this->setIdentity($config['identityClass']);
        }

        if (isset($config['dataBaseSettings'])) {
            $this->setDbConnection($config['dataBaseSettings']);
        }

        $this->setSession();
        $this->setRequest();
        $this->setAction($config['startPageParam']['action']);
        $this->setController($config['startPageParam']['controller']);

    }

    private function setSession()
    {
        self::$session = new Session();
    }

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

    private function setRequest()
    {
        self::$request = new Request();
    }

    private function setIdentity($class) {
        self::$identity = new $class();
    }

    private function setAction($startAction)
    {
        if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index') {
            $this->action = $startAction;
        } else {
            preg_match('/\/(.*)/', $_REQUEST['q'], $matches);
            $this->action = $matches[1];
        }
    }

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

    public function run()
    {
        $this->controller->runAction();
    }



}