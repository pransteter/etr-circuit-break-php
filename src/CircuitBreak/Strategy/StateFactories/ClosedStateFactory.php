<?php

namespace Pransteter\CircuitBreak\Strategy\StateFactories;

use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\State;

class ClosedStateFactory extends StateFactory
{
    public function make(?State $currentState = null): State
    {
        return new ClosedState(
            totalTries: 0,
            triesLimit: 0,
            noTriesTimestampLimit: 0,
        );
    }
}
