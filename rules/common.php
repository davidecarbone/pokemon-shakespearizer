<?php
/**
 * @var Slim\Container $container
 */

use GuzzleHttp\Client;
use PokemonShakespearizer\Configuration\Configuration;

$container['ShakespearizerHttpClient'] = function($container) {
    /** @var Configuration */
    $configuration = $container['Configuration'];

    return new Client([
        'base_uri' => $configuration->get('SHAKESPEARIZER_API_BASE_URL'),
        'timeout' => 10.0,
        'allow_redirects' => false,
    ]);
};

$container['PokemonShakespearizerHttpClient'] = function($container) {
    /** @var Configuration */
    $configuration = $container['Configuration'];

    return new Client([
        'base_uri' => $configuration->get('API_BASE_URL'),
        'timeout' => 10.0,
        'allow_redirects' => false,
    ]);
};

$container['Configuration'] = function() {
    $configuration = include_once(__DIR__ . '/../config/config.php');

    return new Configuration($configuration);
};
