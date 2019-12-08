<?php

namespace PokemonShakespearizer\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PokemonShakespearizer\Controller\PokemonController;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\Pokemon\Pokemon;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslator;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorException;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorTooManyRequestsException;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonControllerTest extends TestCase
{
    /** @var PokemonController */
    private $pokemonController;

    /** @var PokemonTranslator | MockObject */
    private $pokemonTranslatorMock;

    public function setUp()
    {
        parent::setUp();

        $this->pokemonTranslatorMock = $this->createMock(PokemonTranslator::class);
        $this->pokemonController = new PokemonController($this->pokemonTranslatorMock);
    }

    /** @test */
    public function get_pokemon_should_respond_200_with_name_and_description()
    {
        $response = new Response();

        $this->pokemonTranslatorMock
            ->expects($this->once())
            ->method('shakespearize')
            ->willReturn(Pokemon::withNameAndDescription('charizard', 'a shakespearized description'));

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/pokemon',
            'QUERY_STRING' => ''
        ]);
        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('name', 'charizard');

        $response = $this->pokemonController->getPokemon($request, $response);
        $responseContent = $this->getResponseContent($response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $responseContent);
        $this->assertArrayHasKey('description', $responseContent);
    }

    /** @test */
    public function get_pokemon_with_no_name_should_respond_400_with_error()
    {
        $response = new Response();

        $this->pokemonTranslatorMock
            ->expects($this->never())
            ->method('shakespearize');

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/pokemon',
            'QUERY_STRING' => ''
        ]);
        $request = Request::createFromEnvironment($environment);

        $response = $this->pokemonController->getPokemon($request, $response);
        $responseContent = $this->getResponseContent($response);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseContent);
    }

    /** @test */
    public function get_pokemon_should_respond_404_when_a_pokemon_is_not_found()
    {
        $response = new Response();

        $this->pokemonTranslatorMock
            ->expects($this->once())
            ->method('shakespearize')
            ->willThrowException(new PokemonNotFoundException());

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/pokemon',
            'QUERY_STRING' => ''
        ]);
        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('name', 'badName');

        $response = $this->pokemonController->getPokemon($request, $response);
        $responseContent = $this->getResponseContent($response);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseContent);
    }

    /** @test */
    public function get_pokemon_should_respond_429_when_request_limit_is_reached()
    {
        $response = new Response();

        $this->pokemonTranslatorMock
            ->expects($this->once())
            ->method('shakespearize')
            ->willThrowException(new PokemonTranslatorTooManyRequestsException());

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/pokemon',
            'QUERY_STRING' => ''
        ]);
        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('name', 'charizard');

        $response = $this->pokemonController->getPokemon($request, $response);
        $responseContent = $this->getResponseContent($response);

        $this->assertEquals(429, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseContent);
    }

    /** @test */
    public function get_pokemon_should_respond_500_when_translator_fails_for_unexpected_reasons()
    {
        $response = new Response();

        $this->pokemonTranslatorMock
            ->expects($this->once())
            ->method('shakespearize')
            ->willThrowException(new PokemonTranslatorException());

        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/pokemon',
            'QUERY_STRING' => ''
        ]);
        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('name', 'charizard');

        $response = $this->pokemonController->getPokemon($request, $response);
        $responseContent = $this->getResponseContent($response);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertArrayHasKey('error', $responseContent);
    }

    /**
     * @param Response $response
     *
     * @return array
     */
    private function getResponseContent(Response $response): array
    {
        $body = $response->getBody();
        $body->rewind();

        return json_decode($body->getContents(), true);
    }
}
