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
     * @throws NetworkException
     */
    public function retrievePokemonDescriptionByName(string $pokemonName): string
    {
        $pokemonId = $this->client
            ->pokemon($pokemonName)
            ->getId();

        return $this->client
            ->characteristic($pokemonId)
            ->getDescriptions()
            ->getTranslations()
            ->get('en');
    }
}
