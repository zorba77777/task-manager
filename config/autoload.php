<?php
spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\','/',$class_name);
    include __DIR__ . '/../' . $class_name . '.php';
});