<?php

namespace PokemonShakespearizer\Tests\Unit\Pokemon;

use PHPUnit\Framework\TestCase;
use PokemonShakespearizer\Pokemon\Pokemon;

class PokemonTest extends TestCase
{
    /** @test */
    public function can_be_built_with_a_name_and_a_description()
    {
        $pokemon = Pokemon::withNameAndDescription('test name', 'test description');

        $this->assertEquals('test description', $pokemon->description());
    }
}
