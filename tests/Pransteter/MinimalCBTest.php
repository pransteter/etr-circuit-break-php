<?php

namespace Pransteter\MinimalCB;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pransteter\MinimalCB;
use Pransteter\MinimalCB\Contracts\StateRepository;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;
use Pransteter\MinimalCB\Strategy\Strategies\InitialStrategy;
use Pransteter\MinimalCB\Strategy\StrategyIdentifier;
use Pransteter\MinimalCB\Strategy\StrategyProcessor;
use Pransteter\MinimalCB\Transformers\StateTransformer;
use stdClass;

#[CoversClass(MinimalCB::class)]
#[UsesClass(Configuration::class)]
#[UsesClass(StrategyIdentifier::class)]
#[UsesClass(StrategyProcessor::class)]
#[UsesClass(StateTransformer::class)]
#[UsesClass(ClosedState::class)]
#[UsesClass(State::class)]
#[UsesClass(Strategy::class)]
#[UsesClass(InitialStrategy::class)]
class MinimalCBTest extends TestCase
{
    #[DataProvider('initialCaseDataProvider')]
    public function testShouldApplyMinimalCBFirstTimeWhenProcessExecutedAsSuccess(
        bool $processExecutedAsSuccess,
        stdClass $expectedFirstState,
    ): void {
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

        $stateRepository->expects($this->once())
            ->method('saveState')
            ->with(
                $processIdentifier,
                $this->equalTo($expectedFirstState),
            )->willReturn(true);

        // Actions
        $cb->begin();
        $canExecute = $cb->canExecute();
        $cb->end($processExecutedAsSuccess);

        // Assertions
        $this->assertTrue($canExecute);
        $this->assertSame(null, $cb->getCurrentState());
    }

    /**
     * @return array<array<bool|stdClass>>
     */
    public static function initialCaseDataProvider(): array
    {
        $baseState = [
            'name' => 'closed',
            'totalFailedTries' => 0,
            'noTriesTimestampLimit' => null,
        ];

        return [
            'Initial status when execution was success.' => [
                'processExecutedAsSuccess' => true,
                'expectedFirstState' => (object) $baseState,
            ],
            'Initial status when execution was failure.' => [
                'processExecutedAsSuccess' => false,
                'expectedFirstState' => (object) array_merge(
                    $baseState,
                    [ 'totalFailedTries' => 1 ],
                ),
            ],
        ];
    }
}
