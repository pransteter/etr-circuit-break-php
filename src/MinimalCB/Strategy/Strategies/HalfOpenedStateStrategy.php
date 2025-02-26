<?php

namespace Pransteter\MinimalCB\Strategy\Strategies;

use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;

class HalfOpenedStateStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        return $executionWasSuccessful === true
            ? new ClosedState(
                totalFailedTries: 0,
                noTriesTimestampLimit: null,
            )
            : new OpenedState(
                totalFailedTries: null,
                noTriesTimestampLimit: $this->calculateNoTriesTimestampLimit(),
            );
    }
}
