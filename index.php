<?php
ini_set('display_errors', 1);
ini_set('error_reporting', 2047);

use core\App;

require __DIR__ . '/config/autoload.php';
require __DIR__ . '/config/config.php';
$app = new App();
$app->configure($config);
$app->run();
