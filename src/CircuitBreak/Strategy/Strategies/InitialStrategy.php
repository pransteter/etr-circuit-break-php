<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class InitialStrategy extends Strategy
{
    public function getNewState(bool $wasProcessSuccessful): State
    {
        if ($wasProcessSuccessful) {
            return new ClosedState(
                totalTries: $this->configuration->totalTries,
                triesLimit: $this->configuration->triesLimit,
                noTriesTimestampLimit: $this->configuration->noTriesTimestampLimit,
            );
        }

        return new OpenedState(
            totalTries: $this->configuration->totalTries,
            triesLimit: $this->configuration->triesLimit,
            noTriesTimestampLimit: $this->configuration->noTriesTimestampLimit,
        );
    }
}