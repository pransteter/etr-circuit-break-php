<?php

namespace Pransteter\CircuitBreak\Strategy;

use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;
use Pransteter\CircuitBreak\Strategy\Strategies\InitialStrategy;

class StrategyProcessor
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly ?State $lastPersistedState = null,
    ) {
    }

    public function executeStrategy(bool $processWasSuccessful): State
    {
        $strategy = $this->identifyStrategyToBeExecuted();

        return $strategy->getNewState($processWasSuccessful);
    }

    private function identifyStrategyToBeExecuted(): Strategy
    {
        if ($this->lastPersistedState === null) {
            return new InitialStrategy(
                $this->configuration,
                $this->lastPersistedState,
            );
        }

        // continue...
    }
}
