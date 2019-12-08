<?php

namespace PokemonShakespearizer\Controller;

use InvalidArgumentException;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpService;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceRequestLimitReachedException;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonController
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
            $shakespearizedDescription = $this
                ->shakespearizerHttpService
                ->shakespearizeDescription($pokemonName, $pokemon->description());

        } catch (InvalidArgumentException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 400);
        } catch (PokemonNotFoundException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 404);
        } catch (ShakespearizerHttpServiceRequestLimitReachedException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 429);
        }

        return $response->withJson([
            'name' => $pokemon->name(),
            'description' => $shakespearizedDescription
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
