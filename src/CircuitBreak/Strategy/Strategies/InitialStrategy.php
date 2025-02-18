<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class InitialStrategy extends Strategy
{
    public function getNewState(bool $executionWasSuccessful): State
    {
        return new ClosedState(
            totalTries: $executionWasSuccessful ? 0 : 1,
            noTriesTimestampLimit: null,
        );
    }
}
