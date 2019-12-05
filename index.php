<?php

namespace PokemonShakespearizer;

require_once 'vendor/autoload.php';

use Slim\App;
use Slim\Container;

$container = new Container();
require_once 'rules/enabled.php';

$settings = $container->get('settings');
$settings->replace([
    'displayErrorDetails' => true,
    'debug' => true
]);

$app = new App($container);

require_once 'routes/enabled.php';

$app->run();
