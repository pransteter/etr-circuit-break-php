<?php

namespace Pransteter\MinimalCB\Strategy\Strategies;

use Exception;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;

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
        if (!($this->lastPersistedState instanceof ClosedState)) {
            throw new Exception('Last persisted state must be ClosedState.');
        }

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
