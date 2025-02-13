<?php

namespace Pransteter\CircuitBreak;

use PHPUnit\Framework\TestCase;
use Pransteter\CircuitBreak;
use Pransteter\CircuitBreak\Contracts\HasCircuitBreak;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;

class CircuitBreakTest extends TestCase
{
    public function testShouldApplyCircuitBreak(): void
    {
        // Set
        $process = $this->createMock(HasCircuitBreak::class);
        $strategyProcessor = $this->createMock(StrategyProcessor::class);
        $manager = new CircuitBreak($strategyProcessor);

        // Expectations
        $strategyProcessor->expects($this->once())
            ->method('setInitialParameters')
            ->with($process);

        // Actions
        $manager->apply($process);

    }
}
