<?php

namespace PokemonShakespearizer\Tests\Unit\PokemonTranslator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PokeAPI\Exception\NetworkException;
use PokemonShakespearizer\HttpService\PokemonHttpService;
use PokemonShakespearizer\HttpService\PokemonNotFoundException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpService;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceException;
use PokemonShakespearizer\HttpService\ShakespearizerHttpServiceRequestLimitReachedException;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslator;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorException;
use PokemonShakespearizer\PokemonTranslator\PokemonTranslatorTooManyRequestsException;

class PokemonTranslatorTest extends TestCase
{
    /** @var PokemonTranslator */
    private $pokemonTranslator;

    /** @var PokemonHttpService | MockObject */
    private $pokemonHttpServiceMock;

    /** @var ShakespearizerHttpService | MockObject */
    private $shakespearizerHttpServiceMock;

    public function setUp()
    {
        parent::setUp();

        $this->pokemonHttpServiceMock = $this->createMock(PokemonHttpService::class);
        $this->shakespearizerHttpServiceMock = $this->createMock(ShakespearizerHttpService::class);
        $this->pokemonTranslator = new PokemonTranslator($this->pokemonHttpServiceMock, $this->shakespearizerHttpServiceMock);
    }

    /** @test */
    public function it_returns_a_pokemon_with_a_shakespearized_description()
    {
        $pokemonName = 'charizard';

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonDescriptionByName')
            ->with($pokemonName)
            ->willReturn('a description');

        $this->shakespearizerHttpServiceMock
            ->expects($this->once())
            ->method('shakespearizeDescription')
            ->with($pokemonName, 'a description')
            ->willReturn('a shakespearized description');

        $pokemon = $this->pokemonTranslator->shakespearize($pokemonName);

        $this->assertEquals($pokemonName, $pokemon->name());
        $this->assertEquals('a shakespearized description', $pokemon->description());
    }

    /** @test */
    public function it_throws_a_meaningful_exception_when_pokemon_http_service_cannot_find_a_pokemon()
    {
        $this->expectException(PokemonNotFoundException::class);

        $pokemonName = 'charizard';

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonDescriptionByName')
            ->willThrowException(new NetworkException());

        $this->shakespearizerHttpServiceMock
            ->expects($this->never())
            ->method('shakespearizeDescription');

        $this->pokemonTranslator->shakespearize($pokemonName);
    }

    /** @test */
    public function it_throws_a_meaningful_exception_when_shakespearizer_http_service_reaches_request_limit()
    {
        $this->expectException(PokemonTranslatorTooManyRequestsException::class);

        $pokemonName = 'charizard';

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonDescriptionByName')
            ->with($pokemonName)
            ->willReturn('a description');

        $this->shakespearizerHttpServiceMock
            ->expects($this->once())
            ->method('shakespearizeDescription')
            ->willThrowException(new ShakespearizerHttpServiceRequestLimitReachedException());

        $this->pokemonTranslator->shakespearize($pokemonName);
    }

    /** @test */
    public function it_throws_a_meaningful_exception_when_shakespearizer_http_service_cannot_translate_a_description()
    {
        $this->expectException(PokemonTranslatorException::class);

        $pokemonName = 'charizard';

        $this->pokemonHttpServiceMock
            ->expects($this->once())
            ->method('retrievePokemonDescriptionByName')
            ->with($pokemonName)
            ->willReturn('a description');

        $this->shakespearizerHttpServiceMock
            ->expects($this->once())
            ->method('shakespearizeDescription')
            ->willThrowException(new ShakespearizerHttpServiceException());

        $this->pokemonTranslator->shakespearize($pokemonName);
    }
}
