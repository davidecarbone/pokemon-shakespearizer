<?php

namespace PokemonShakespearizer\Tests\End2End;

use GuzzleHttp\Client;
use PokemonShakespearizer\Test\End2End\ContainerAwareTest;

class PokemonShakespearizerTest extends ContainerAwareTest
{
    /** @var Client */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->container->get('PokemonShakespearizerHttpClient');
    }

    /** @test */
    public function get_pokemon_responds_with_a_shakespearized_description()
    {
        $response = $this->client->get('pokemon/1');
        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $responseBody);
        $this->assertArrayHasKey('description', $responseBody);
    }
}
