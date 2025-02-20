<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use DateTime;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class OpenedStateStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        if ($this->isNoTriesTimestampLimitExpired()) {
            return new HalfOpenedState(
                totalFailedTries: null,
                noTriesTimestampLimit: null,
            );
        }

        return clone $this->lastPersistedState;
    }

    private function isNoTriesTimestampLimitExpired(): bool
    {
        $now = (new DateTime('now'))->getTimestamp();
        $limit = $this->lastPersistedState->getNoTriesTimestampLimit();

        return $now > $limit;
    }
}