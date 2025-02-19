<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class HalfOpenedStateStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        return $executionWasSuccessful === true
            ? new ClosedState(
                totalFailedTries: 0,
                noTriesTimestampLimit: null,
            )
            : clone $this->lastPersistedState;
    }
}
