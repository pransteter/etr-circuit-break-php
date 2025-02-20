<?php

namespace Pransteter\CircuitBreak\Strategy;

use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\Contracts\Strategy;
use Pransteter\CircuitBreak\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\HalfOpenedStateStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\InitialStrategy;
use Pransteter\CircuitBreak\Strategy\Strategies\OpenedStateStrategy;

class StrategyIdentifier
{
    public function __construct(
        private readonly Configuration $configuration
    )
    {
    }

    public function identityByCurrentState(?State $currentState = null): Strategy
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
            case $currentState instanceof OpenedState:
                return new OpenedStateStrategy(
                    $this->configuration,
                    $currentState,
                );
        }
    }
}
