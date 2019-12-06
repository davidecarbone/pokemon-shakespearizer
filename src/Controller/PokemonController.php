<?php

namespace PokemonShakespearizer\Controller;

use InvalidArgumentException;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonController
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function getPokemon(Request $request, Response $response): Response
    {
        $pokemonName = $request->getAttribute('name');

        try {
            $this->assertPokemonNameIsNotEmpty($pokemonName);
        } catch (InvalidArgumentException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 400);
        }

        return $response->withJson([
            'name' => $pokemonName,
            'description' => 'dummy'
        ], 200);
    }

    /**
     * @param string|null $pokemonName
     *
     * @throws InvalidArgumentException
     */
    private function assertPokemonNameIsNotEmpty(?string $pokemonName)
    {
        if (empty($pokemonName)) {
            throw new InvalidArgumentException('Pokemon name is required.');
        }
    }
}
