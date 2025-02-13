<?php

namespace Pransteter\CircuitBreak\Strategy\StateFactories;

use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\DTOs\State;

class OpenedStateFactory extends StateFactory
{
    public function make(?State $currentState = null): State
    {
        return new OpenedState(
            totalTries: 0,
            triesLimit: 0,
            noTriesTimestampLimit: 0,
        );
    }
}
