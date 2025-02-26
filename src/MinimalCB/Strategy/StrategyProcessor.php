<?php

namespace Pransteter\MinimalCB\Strategy;

use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;
use Pransteter\MinimalCB\Strategy\Strategies\InitialStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\HalfOpenedStateStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\OpenedStateStrategy;

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
