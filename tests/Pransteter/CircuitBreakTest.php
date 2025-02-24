<?php

namespace Pransteter\CircuitBreak;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pransteter\CircuitBreak;
use Pransteter\CircuitBreak\Contracts\StateRepository;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\Strategy\StrategyIdentifier;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;
use Pransteter\CircuitBreak\Transformers\StateTransformer;

#[CoversClass(CircuitBreak::class)]
#[UsesClass(Configuration::class)]
#[UsesClass(StrategyIdentifier::class)]
#[UsesClass(StrategyProcessor::class)]
#[UsesClass(StateTransformer::class)]
class CircuitBreakTest extends TestCase
{
    // #[CoversMethod(CircuitBreak::class, 'begin')]
    // #[Test]
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
