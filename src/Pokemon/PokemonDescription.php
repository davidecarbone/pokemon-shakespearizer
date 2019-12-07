<?php

namespace PokemonShakespearizer\Pokemon;

final class PokemonDescription
{
    /** @var string */
    private $description;

    /**
     * @param string $description
     */
    public function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }
}
