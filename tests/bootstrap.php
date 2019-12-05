<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Slim\Container;

$container = new Container();
require_once __DIR__ . '/../routes/enabled.php';

$settings = $container->get('settings');
$settings->replace([
    'displayErrorDetails' => false,
    'debug' => false
]);
$app = new App($container);
