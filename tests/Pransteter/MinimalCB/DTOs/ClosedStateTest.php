<?php

namespace Pransteter\MinimalCB\DTOs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClosedState::class)]
class ClosedStateTest extends TestCase
{
    public function testShouldReturnStateName(): void
    {
        // Set
        $state = new ClosedState(
            totalFailedTries: null,
            noTriesTimestampLimit: null,
        );

        // Actions
        $result = $state->getName();

        // Assertions
        $this->assertEquals('closed', $result);
    }
}
