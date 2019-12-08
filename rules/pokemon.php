<?php
/**
 * @var Slim\Container $container
 */

use Gilbitron\Util\SimpleCache;
use GuzzleHttp\Client;
use PokemonShakespearizer\Configuration\Configuration;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\ShakespearizerHttpService;

$container['PokemonHttpService'] = function() {
    $pokeApiClient = new \PokeAPI\Client();

    return new PokemonHttpService($pokeApiClient);
};

$container['ShakespearizerHttpService'] = function($container) {
    /** @var Configuration */
    $configuration = $container['Configuration'];

    $client = new Client([
        'base_uri' => $configuration->get('SHAKESPEARIZE_API_BASE_URL'),
        'timeout' => 10.0,
        'allow_redirects' => false,
    ]);

    $cache = new SimpleCache();
    $cache->cache_path = '.cache/';

    return new ShakespearizerHttpService($client, $cache);
};

$container['Configuration'] = function() {
    $configuration = include_once(__DIR__ . '/../config/config.php');

    return new Configuration($configuration);
};
