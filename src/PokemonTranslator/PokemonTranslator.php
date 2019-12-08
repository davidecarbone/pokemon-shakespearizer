<?php

namespace PokemonShakespearizer\PokemonTranslator;

use PokeAPI\Exception\NetworkException;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpService;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceRequestLimitReachedException;
use PokemonShakespearizer\Pokemon\Pokemon;

class PokemonTranslator
{
    /** @var PokemonHttpService */
    private $pokemonHttpService;

    /** @var ShakespearizerHttpService */
    private $shakespearizerHttpService;

    /**
     * @param PokemonHttpService        $pokemonHttpService
     * @param ShakespearizerHttpService $shakespearizerHttpService
     */
    public function __construct(PokemonHttpService $pokemonHttpService, ShakespearizerHttpService $shakespearizerHttpService)
    {
        $this->pokemonHttpService = $pokemonHttpService;
        $this->shakespearizerHttpService = $shakespearizerHttpService;
    }

    /**
     * @param string $pokemonName
     *
     * @return Pokemon
     * @throws PokemonNotFoundException
     * @throws PokemonTranslatorException
     * @throws PokemonTranslatorTooManyRequestsException
     */
    public function shakespearize(string $pokemonName): Pokemon
    {
        try {
            $pokemonDescription = $this->pokemonHttpService->retrievePokemonDescriptionByName($pokemonName);
        } catch (NetworkException $exception) {
            throw new PokemonNotFoundException("No pokemon found with the name '$pokemonName'");
        }

        try {
            $shakespearizedDescription = $this
                ->shakespearizerHttpService
                ->shakespearizeDescription($pokemonName, $pokemonDescription);
        } catch (ShakespearizerHttpServiceRequestLimitReachedException $exception) {
            throw new PokemonTranslatorTooManyRequestsException($exception->getMessage());
        } catch (ShakespearizerHttpServiceException $exception) {
            throw new PokemonTranslatorException($exception->getMessage());
        }

        return Pokemon::withNameAndDescription($pokemonName, $shakespearizedDescription);
    }
}
