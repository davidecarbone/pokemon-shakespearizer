<?php

namespace PokemonShakespearizer\Configuration;

class Configuration
{
    /** @var array */
    private $values;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param string $key
     * @throws MissingConfigurationValueException
     * @return mixed
     */
    public function get(string $key)
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }
        throw new MissingConfigurationValueException($key);
    }
}
