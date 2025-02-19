<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use DateTime;
use DateInterval;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class ClosedStateStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        if (!$executionWasSuccessful) {
            return $this->getNewStateWhenExecutionWasNotSuccessful();
        }

        return new ClosedState(
            totalFailedTries: 0,
            noTriesTimestampLimit: null,
        );
    }

    private function getNewStateWhenExecutionWasNotSuccessful(): State
    {
        $totalFailedTries = $this->lastPersistedState->getTotalFailedTries() ?? 0;

        if ($totalFailedTries === $this->configuration->failedTriesLimit) {
            return new OpenedState(
                totalFailedTries: null,
                noTriesTimestampLimit: $this->calculateNoTriesTimestampLimit(),
            );
        }

        return new ClosedState(
            totalFailedTries: $totalFailedTries + 1,
            noTriesTimestampLimit: null,
        );
    }
}