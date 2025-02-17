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
    ) {
    }

    public function processStrategy(
        ?State $currentState = null,
        ?bool $executionWasSuccessful = null,
    ): State {
        $strategy = $this->identifyStrategyToBeProcessed();

        return $strategy->getNewState($executionWasSuccessful);
    }

    // create class to identify strategy to be processed
    private function identifyStrategyToBeProcessed(
        ?State $currentState = null,
        ?bool $executionWasSuccessful = null,
    ): Strategy
    {
        if ($currentState === null) {
            return new InitialStrategy(
                $this->configuration,
                $currentState,
            );
        }

        // continue...
    }
}
