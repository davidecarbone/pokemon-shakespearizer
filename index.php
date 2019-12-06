<?php

namespace PokemonShakespearizer;

require_once 'vendor/autoload.php';

use Psr7Middlewares\Middleware\TrailingSlash;
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
$app->add(new TrailingSlash(false));

require_once 'routes/enabled.php';

$app->run();
