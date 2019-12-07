<?php

namespace PokemonShakespearizer\Tests\Unit\Pokemon;

use PHPUnit\Framework\TestCase;
use PokemonShakespearizer\Pokemon\PokemonDescription;

class PokemonDescriptionTest extends TestCase
{
    /** @test */
    public function can_be_converted_to_string()
    {
        $pokemonDescription = new PokemonDescription('test description');

        $this->assertEquals('test description', $pokemonDescription);
    }
}
