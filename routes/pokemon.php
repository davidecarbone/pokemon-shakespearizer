<?php

use PokemonShakespearizer\Controller\PokemonController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/pokemon', function (Request $request, Response $response) {
    $pokemonHttpService = $this->get('PokemonHttpService');
    $shakespearizerHttpService = $this->get('ShakespearizerHttpService');
    $controller = new PokemonController($pokemonHttpService, $shakespearizerHttpService);

    return $controller->getPokemon($request, $response);
});

$app->get('/pokemon/{name}', function (Request $request, Response $response) {
    $pokemonHttpService = $this->get('PokemonHttpService');
    $shakespearizerHttpService = $this->get('ShakespearizerHttpService');
    $controller = new PokemonController($pokemonHttpService, $shakespearizerHttpService);

    return $controller->getPokemon($request, $response);
});
