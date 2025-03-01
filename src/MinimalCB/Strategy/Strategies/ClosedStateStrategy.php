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
        if (!($this->lastPersistedState instanceof ClosedState)) {
            throw new Exception('Last persisted state must be ClosedState.');
        }

        $totalFailedTries = $this->lastPersistedState->getTotalFailedTries() ?? 0;

        if (!$executionWasSuccessful) {
            $totalFailedTries++;
        }

        return $this->getNewStateConsideringFailedTriesLimit($totalFailedTries);
    }

    private function getNewStateConsideringFailedTriesLimit(int $totalFailedTries): State
    {
        if ($totalFailedTries >= $this->configuration->failedTriesLimit) {
            return new OpenedState(
                totalFailedTries: null,
                noTriesTimestampLimit: $this->calculateNoTriesTimestampLimit(),
            );
        }

        return new ClosedState(
            totalFailedTries: $totalFailedTries,
            noTriesTimestampLimit: null,
        );
    }
}
