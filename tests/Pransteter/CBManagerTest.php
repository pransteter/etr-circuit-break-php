<?php

namespace Pransteter;

use PHPUnit\Framework\TestCase;

class CBManagerTest extends TestCase
{
    public function testSayHello(): void
    {
        // Set
        $manager = new CBManager();

        // Actions
        $message = $manager->sayHello();

        // Assertions
        $this->assertSame('Hello World!', $message);

    }
}