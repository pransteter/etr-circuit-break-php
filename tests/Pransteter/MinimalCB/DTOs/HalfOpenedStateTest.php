<?php

namespace Pransteter\MinimalCB\DTOs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HalfOpenedState::class)]
final class HalfOpenedStateTest extends TestCase
{
    public function testShouldReturnStateName(): void
    {
        // Set
        $state = new HalfOpenedState(
            totalFailedTries: null,
            noTriesTimestampLimit: null,
        );

        // Actions
        $result = $state->getName();

        // Assertions
        $this->assertEquals('halfOpened', $result);
    }
}
