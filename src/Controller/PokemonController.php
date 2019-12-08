<?php

namespace PokemonShakespearizer\Controller;

use InvalidArgumentException;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceRequestLimitReachedException;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslator;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorException;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorTooManyRequestsException;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonController
{
    /** @var PokemonTranslator */
    private $pokemonTranslator;

    /**
     * @param PokemonTranslator $pokemonTranslator
     */
    public function __construct(PokemonTranslator $pokemonTranslator)
    {
        $this->pokemonTranslator = $pokemonTranslator;
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

            $pokemon = $this->pokemonTranslator->shakespearize($pokemonName);

        } catch (InvalidArgumentException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 400);
        } catch (PokemonNotFoundException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 404);
        } catch (PokemonTranslatorTooManyRequestsException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 429);
        } catch (PokemonTranslatorException $exception) {
            return $response->withJson([
                'error' => $exception->getMessage()
            ], 500);
        }

        return $response->withJson([
            'name' => $pokemon->name(),
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
