<?php

use PokemonShakespearizer\Controller\PokemonController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/pokemon', function (Request $request, Response $response) {
    $pokemonTranslator = $this->get('PokemonTranslator');
    $controller = new PokemonController($pokemonTranslator);

    return $controller->getPokemon($request, $response);
});

$app->get('/pokemon/{name}', function (Request $request, Response $response) {
    $pokemonTranslator = $this->get('PokemonTranslator');
    $controller = new PokemonController($pokemonTranslator);

    return $controller->getPokemon($request, $response);
});
