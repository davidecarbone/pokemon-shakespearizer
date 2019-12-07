<?php

namespace PokemonShakespearizer\Pokemon;

final class Pokemon
{
    /** @var string */
    private $name;

    /** @var PokemonDescription */
    private $description;

    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param string $description
     *
     * @return Pokemon
     */
    public static function withNameAndDescription(string $name, string $description): Pokemon
    {
        $pokemon = new self();
        $pokemon->name = $name;
        $pokemon->description = new PokemonDescription($description);

        return $pokemon;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }
}
