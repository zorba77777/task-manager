<?php

$config = [
    'identityClass' => 'models\User',

    'dataBaseSettings' => [
        'host' => 'localhost',
        'userName' => 'root',
        'password' => '123456',
        'dbName' => 'task_manager',
        'charset' => 'utf8mb4'
    ],

    'startPageParam' => [
        'controller' => 'controllers\MainController',
        'action' => 'index'
    ]
];