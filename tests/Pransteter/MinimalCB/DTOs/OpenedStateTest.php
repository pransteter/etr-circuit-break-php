<?php

namespace Pransteter\MinimalCB\DTOs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenedState::class)]
final class OpenedStateTest extends TestCase
{
    public function testShouldReturnStateName(): void
    {
        // Set
        $state = new OpenedState(
            totalFailedTries: null,
            noTriesTimestampLimit: null,
        );

        // Actions
        $result = $state->getName();

        // Assertions
        $this->assertEquals('opened', $result);
    }
}
