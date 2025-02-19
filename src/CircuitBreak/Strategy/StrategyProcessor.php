<?php

namespace Pransteter\CircuitBreak\Strategy;

use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;
use Pransteter\CircuitBreak\Strategy\Strategies\InitialStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\HalfOpenedStateStrategy;

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
        $strategy = $this->identifyStrategyToBeProcessed($currentState);

        return $strategy->getNewState($executionWasSuccessful);
    }

    // create class to identify strategy to be processed
    private function identifyStrategyToBeProcessed(?State $currentState = null): Strategy
    {
        switch($currentState) {
            case null:
                return new InitialStrategy(
                    $this->configuration,
                    $currentState,
                );
            case $currentState instanceof ClosedState:
                return new ClosedStateStrategy(
                    $this->configuration,
                    $currentState,
                );
            case $currentState instanceof HalfOpenedState:
                return new HalfOpenedStateStrategy(
                    $this->configuration,
                    $currentState,
                );
            // case $currentState instanceof OpenedState:
            //     return new OpenedStateStrategy(
            //         $this->configuration,
            //         $currentState,
            //     );
        }
    }
}
