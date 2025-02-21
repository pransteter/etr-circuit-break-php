<?php

namespace Pransteter\CircuitBreak;

use PHPUnit\Framework\TestCase;
use Pransteter\CircuitBreak;
use Pransteter\CircuitBreak\Contracts\HasCircuitBreak;
use Pransteter\CircuitBreak\Contracts\StateRepository;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;

class CircuitBreakTest extends TestCase
{
    public function testShouldApplyCircuitBreakFirstTime(): void
    {
        // Set
        $processIdentifier = 'test-1';
        $configuration = new Configuration(
            processIdentifier: $processIdentifier,
            failedTriesLimit: 3,
            secondsToStayOpened: 5,
        );
        $stateRepository = $this->createMock(StateRepository::class);
        $cb = new CircuitBreak($configuration, $stateRepository);

        // Expectations
        $stateRepository->expects($this->once())
            ->method('getState')
            ->with($processIdentifier)
            ->willReturn(null);

        // Actions
        $cb->begin();
        $result = $cb->canExecute();

        // Assertions
        $this->assertTrue($result);
    }
}
