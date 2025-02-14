<?php

namespace Pransteter\CircuitBreak\Strategy\Contracts;

use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\State;

abstract class Strategy
{
    public function __construct(
        protected readonly Configuration $configuration,
        protected readonly ?State $lastPersistedState = null,
    ) {
    }

    abstract public function getNewState(bool $wasProcessSuccessful): State;
}
