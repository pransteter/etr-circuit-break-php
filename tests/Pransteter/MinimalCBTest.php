<?php

namespace Pransteter\MinimalCB;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pransteter\MinimalCB;
use Pransteter\MinimalCB\Contracts\StateRepository;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\Strategy\StrategyIdentifier;
use Pransteter\MinimalCB\Strategy\StrategyProcessor;
use Pransteter\MinimalCB\Transformers\StateTransformer;

#[CoversClass(MinimalCB::class)]
#[UsesClass(Configuration::class)]
#[UsesClass(StrategyIdentifier::class)]
#[UsesClass(StrategyProcessor::class)]
#[UsesClass(StateTransformer::class)]
class MinimalCBTest extends TestCase
{
    public function testShouldApplyMinimalCBFirstTime(): void
    {
        // Set
        $processIdentifier = 'test-1';
        $configuration = new Configuration(
            processIdentifier: $processIdentifier,
            failedTriesLimit: 3,
            secondsToStayOpened: 5,
        );
        $stateRepository = $this->createMock(StateRepository::class);
        $cb = new MinimalCB($configuration, $stateRepository);

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
