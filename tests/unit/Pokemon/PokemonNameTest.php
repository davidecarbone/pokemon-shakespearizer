<?php

namespace PokemonShakespearizer\Tests\Unit\Pokemon;

use PHPUnit\Framework\TestCase;
use PokemonShakespearizer\Pokemon\PokemonName;

class PokemonNameTest extends TestCase
{
    /** @test */
    public function can_be_converted_to_string()
    {
        $pokemonName = new PokemonName('test name');

        $this->assertEquals('test name', $pokemonName);
    }
}
