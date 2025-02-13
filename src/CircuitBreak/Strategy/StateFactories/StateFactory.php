<?php

namespace Pransteter\CircuitBreak\Strategy\StateFactories;

use Pransteter\CircuitBreak\DTOs\State;

abstract class StateFactory
{
    abstract public function make(?State $currentState = null): State;
}
