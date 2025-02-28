<?php

namespace Pransteter\MinimalCB\Strategy;

use Exception;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;
use Pransteter\MinimalCB\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\HalfOpenedStateStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\InitialStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\OpenedStateStrategy;

class StrategyIdentifier
{
    public function __construct(
        private readonly Configuration $configuration
    ) {
    }

    public function identityByCurrentState(?State $currentState = null): Strategy
    {
        if (is_null($currentState)) {
            return new InitialStrategy(
                $this->configuration,
                $currentState,
            );
        }

        switch ($currentState) {
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
            default:
                throw new Exception('Unknown state.');
        }
    }
}
