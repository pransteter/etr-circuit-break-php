<?php

namespace Pransteter\CircuitBreak\Strategy\Contracts;

use DateInterval;
use DateTime;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\State;

abstract class Strategy
{
    abstract public function getNewState(?bool $executionWasSuccessful = null): State;

    public function __construct(
        protected readonly Configuration $configuration,
        protected readonly ?State $lastPersistedState = null,
    ) {
    }

    protected function calculateNoTriesTimestampLimit(): int
    {
        $now = new DateTime('now');
        $interval = DateInterval::createFromDateString(
            sprintf('%d seconds', $this->configuration->secondsToStayOpened),
        );

        $noTriesDateLimit = $now->add($interval);

        return $noTriesDateLimit->getTimestamp();
    }
}
