<?php

namespace Pransteter\CircuitBreak\Strategy\StateFactories;

use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\State;

class HalfOpenedStateFactory extends StateFactory
{
    public function make(?State $currentState = null): State
    {
        return new HalfOpenedState(
            totalTries: 0,
            triesLimit: 0,
            noTriesTimestampLimit: 0,
        );
    }
}
