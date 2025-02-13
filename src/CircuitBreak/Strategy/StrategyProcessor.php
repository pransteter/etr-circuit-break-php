<?php

namespace Pransteter\CircuitBreak\Strategy;

use Pransteter\CircuitBreak\Contracts\HasCircuitBreak;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\StateFactories\ClosedStateFactory;
use Pransteter\CircuitBreak\Strategy\StateFactories\StateFactory;

class StrategyProcessor
{
    private string $processName;

    public function setInitialParameters(HasCircuitBreak $process): void
    {
        $this->processName = $process->getProcessName();

        // check if process exists in persistence location.

        // if not, create it:
        $initialState = $this->getState(new ClosedStateFactory());
    }

    public function getState(
        StateFactory $stateFactory,
        ?State $currentState = null,
    ): State {
        return $stateFactory->make($currentState);
    }
}
