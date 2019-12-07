<?php

namespace PokemonShakespearizer\HttpService;

use PokeAPI\Client;
use PokeAPI\Exception\NetworkException;
use PokemonShakespearizer\Pokemon\Pokemon;

class PokemonHttpService
{
    /** @var Client */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $pokemonName
     *
     * @return Pokemon
     * @throws PokemonNotFoundException
     */
    public function retrievePokemonByName(string $pokemonName): Pokemon
    {
        try {
            $pokemonId = $this->client
                ->pokemon($pokemonName)
                ->getId();

            $pokemonDescription = $this->client
                ->characteristic($pokemonId)
                ->getDescriptions()
                ->getTranslations()
                ->get('en');

            return Pokemon::withNameAndDescription($pokemonName, $pokemonDescription);
        } catch (NetworkException $exception) {
            throw new PokemonNotFoundException("No pokemon found with the name '$pokemonName'");
        }
    }
}
