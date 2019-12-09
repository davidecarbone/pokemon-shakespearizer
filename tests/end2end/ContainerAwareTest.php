<?php

namespace PokemonShakespearizer\Test\End2End;

use PHPUnit\Framework\TestCase;
use Slim\Container;

abstract class ContainerAwareTest extends TestCase
{
    /** @var Container */
    protected $container;

    protected function setUp()
    {
        parent::setUp();
        global $app; // quick way to replace old Slim\App::getInstance()

        $this->container = $app->getContainer();
    }
}
