<?php

namespace Pransteter\MinimalCB\Strategy\Strategies;

use DateTime;
use Exception;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;

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
