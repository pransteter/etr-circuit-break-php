<?php

namespace Pransteter\CircuitBreak\Strategy;

use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;
use Pransteter\CircuitBreak\Strategy\Strategies\InitialStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\HalfOpenedStateStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\OpenedStateStrategy;

class StrategyProcessor
{
   public function __construct(
        private readonly StrategyIdentifier $strategyIdentifier,
    ) {
    }

    public function processStrategy(
        ?State $currentState = null,
        ?bool $executionWasSuccessful = null,
    ): State {
        $strategy = $this->strategyIdentifier->identityByCurrentState($currentState);

        return $strategy->getNewState($executionWasSuccessful);
    }
}
