<?php

namespace PokemonShakespearizer\Controller;

use InvalidArgumentException;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonController
{
    /** @var PokemonHttpService */
    private $pokemonHttpService;

    /**
     * @param PokemonHttpService $pokemonHttpService
     */
    public function __construct(PokemonHttpService $pokemonHttpService)
    {
        $this->pokemonHttpService = $pokemonHttpService;
    }

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

            $pokemon = $this->pokemonHttpService->retrievePokemonByName($pokemonName);
        } catch (InvalidArgumentException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 400);
        } catch (PokemonNotFoundException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 404);
        }

        return $response->withJson([
            'name' => $pokemonName,
            'description' => $pokemon->description()
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
