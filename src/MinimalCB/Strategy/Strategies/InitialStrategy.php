<?php

namespace Pransteter\MinimalCB\Strategy\Strategies;

use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;

class InitialStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        return new ClosedState(
            totalFailedTries: $executionWasSuccessful ? 0 : 1,
            noTriesTimestampLimit: null,
        );
    }
}
