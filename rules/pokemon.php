<?php
/**
 * @var Slim\Container $container
 */

use Gilbitron\Util\SimpleCache;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\ShakespearizerHttpService;

$container['PokemonHttpService'] = function() {
    $pokeApiClient = new \PokeAPI\Client();

    return new PokemonHttpService($pokeApiClient);
};

$container['ShakespearizerHttpService'] = function($container) {

    $client = $container['ShakespearizerHttpClient'];
    $cache = new SimpleCache();
    $cache->cache_path = '.cache/';

    return new ShakespearizerHttpService($client, $cache);
};
