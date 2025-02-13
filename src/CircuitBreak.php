<?php

namespace Pransteter;

use Pransteter\CircuitBreak\Contracts\HasCircuitBreak;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;

class CircuitBreak
{
    public function __construct(
        private readonly StrategyProcessor $strategyProcessor = new StrategyProcessor(),
    ) {
    }
    
    public function apply(HasCircuitBreak $process): void
    {
        $this->strategyProcessor->setInitialParameters($process);
    }
}
