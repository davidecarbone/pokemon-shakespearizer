<?php

use PokemonShakespearizer\HttpService\PokemonHttpService;

$container['PokemonHttpService'] = function() {
    $pokeApiClient = new \PokeAPI\Client();

    return new PokemonHttpService($pokeApiClient);
};
