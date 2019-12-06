<?php

namespace PokemonShakespearizer\HttpService;

use PokeAPI\Client;

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
        } catch (\Exception $exception) {
            die('err');
        }
    }
}
