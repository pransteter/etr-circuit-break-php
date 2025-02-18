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
    public function getNewState(bool $executionWasSuccessful): State
    {
        if (!$executionWasSuccessful) {
            return $this->getNewStateWhenExecutionWasNotSuccessful();
        }

        return new ClosedState(
            totalTries: 0,
            noTriesTimestampLimit: null,
        );
    }

    private function getNewStateWhenExecutionWasNotSuccessful(): State
    {
        $totalTries = $this->lastPersistedState->getTotalTries() ?? 0;

        if ($totalTries === $this->configuration->failedTriesLimit) {
            return new OpenedState(
                totalTries: null,
                noTriesTimestampLimit: $this->calculateNoTriesTimestampLimit(),
            );
        }

        return new ClosedState(
            totalTries: $totalTries++,
            noTriesTimestampLimit: null,
        );
    }

    private function calculateNoTriesTimestampLimit(): int
    {
        $now = new DateTime('now');
        $interval = DateInterval::createFromDateString(
            sprintf('%d seconds', $this->configuration->secondsToStayOpened),
        );

        $noTriesDateLimit = $now->add($interval);

        return $noTriesDateLimit->getTimestamp();
    }
}