<?php

namespace App\Tests;

use App\Interfaces\JokeInterface;
use App\Service\ICBDB\RandomJoke;
use PHPUnit\Framework\TestCase;

class RandomJokeTest extends TestCase
{
    public function testRandomJoke()
    {
        $joke = new RandomJoke();

        $this->assertInstanceOf(JokeInterface::class, $joke);

        $this->assertIsArray($joke->getJoke(['nerdy']));

        $this->assertIsArray($joke->getJoke());
    }
}
