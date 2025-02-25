<?php

namespace Pransteter\CircuitBreak\Strategy\Strategies;

use DateTime;
use Exception;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;

class OpenedStateStrategy extends Strategy
{
    public function getNewState(?bool $executionWasSuccessful = null): State
    {
        if (!($this->lastPersistedState instanceof OpenedState)) {
            throw new Exception('Last persisted state must be OpenedState.');
        }

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
        if (!($this->lastPersistedState instanceof OpenedState)) {
            throw new Exception('Last persisted state must be OpenedState.');
        }

        $now = (new DateTime('now'))->getTimestamp();
        $limit = $this->lastPersistedState->getNoTriesTimestampLimit();

        return $now > $limit;
    }
}
