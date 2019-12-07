<?php

namespace PokemonShakespearizer\HttpService;

use PokeAPI\Client;
use PokeAPI\Exception\NetworkException;

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
     * @return string
     * @throws PokemonNotFoundException
     */
    public function retrievePokemonDescriptionByName(string $pokemonName): string
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

            return $pokemonDescription;
        } catch (NetworkException $exception) {
            throw new PokemonNotFoundException("No pokemon found with the name '$pokemonName'");
        }
    }
}
