<?php

namespace PokemonShakespearizer\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PokemonShakespearizer\Controller\PokemonController;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\Pokemon\Pokemon;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class PokemonControllerTest extends TestCase
{
    /** @var PokemonController */
    private $pokemonController;

    /** @var PokemonHttpService | MockObject */
    private $pokemonHttpServiceMock;

    public function setUp()
    {
        parent::setUp();

        $this->pokemonHttpServiceMock = $this->createMock(PokemonHttpService::class);
        $this->pokemonController = new PokemonController($this->pokemonHttpServiceMock);
    }

    /** @test */
    public function get_pokemon_should_respond_200_with_name_and_description()
    {
        $response = new Response();

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonByName')
            ->willReturn(Pokemon::withNameAndDescription('charizard', 'a description'));

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

        $this->pokemonHttpServiceMock
            ->expects($this->never())
            ->method('retrievePokemonByName');

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
    public function get_pokemon_should_respond_404_when_http_service_does_not_return_a_description()
    {
        $response = new Response();

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonByName')
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
