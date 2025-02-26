<?php

namespace Pransteter\MinimalCB\Strategy\Contracts;

use DateInterval;
use DateTime;
use Exception;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\DTOs\State;

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

        if ($interval === false) {
            throw new Exception('Failed to create date interval between now and secondsToStayOpened.');
        }

        $noTriesDateLimit = $now->add($interval);

        return $noTriesDateLimit->getTimestamp();
    }
}
