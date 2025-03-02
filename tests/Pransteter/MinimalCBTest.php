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
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\Contracts\Strategy;
use Pransteter\MinimalCB\Strategy\Strategies\ClosedStateStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\InitialStrategy;
use Pransteter\MinimalCB\Strategy\Strategies\OpenedStateStrategy;
use Pransteter\MinimalCB\Strategy\StrategyIdentifier;
use Pransteter\MinimalCB\Strategy\StrategyProcessor;
use Pransteter\MinimalCB\Transformers\StateIdentifier;
use Pransteter\MinimalCB\Transformers\StateTransformer;
use Pransteter\MinimalCB\Validators\StateValidator;
use stdClass;

#[CoversClass(MinimalCB::class)]
#[UsesClass(Configuration::class)]
#[UsesClass(StrategyIdentifier::class)]
#[UsesClass(StrategyProcessor::class)]
#[UsesClass(StateTransformer::class)]
#[UsesClass(StateIdentifier::class)]
#[UsesClass(StateValidator::class)]
#[UsesClass(State::class)]
#[UsesClass(ClosedState::class)]
#[UsesClass(OpenedState::class)]
#[UsesClass(HalfOpenedState::class)]
#[UsesClass(Strategy::class)]
#[UsesClass(InitialStrategy::class)]
#[UsesClass(ClosedStateStrategy::class)]
#[UsesClass(OpenedStateStrategy::class)]
class MinimalCBTest extends TestCase
{
    #[DataProvider('initialCaseDataProvider')]
    public function testShouldApplyMinimalCBFirstTime(
        bool $processExecutedAsSuccess,
        ?int $expectedTotalFailedTries,
    ): void {
        // Set
        $processIdentifier = 'test-1';
        $configuration = new Configuration(
            processIdentifier: $processIdentifier,
            failedTriesLimit: 3,
            secondsToStayOpened: 5,
        );
        $expectedFirstState = (object) [
            'name' => 'closed',
            'totalFailedTries' => $expectedTotalFailedTries,
            'noTriesTimestampLimit' => null,
        ];
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
        $currentState = $cb->getCurrentState();

        // Assertions
        $this->assertTrue($canExecute);
        $this->assertEquals(
            new ClosedState(
                totalFailedTries: $expectedTotalFailedTries,
                noTriesTimestampLimit: null,
            ),
            $currentState,
        );
    }

    #[DataProvider('closedCaseDataProvider')]
    public function testShouldApplyMinimalCBCircuitIsClosed(
        bool $processExecutedAsSuccess,
        int $persistedTotalFailedTries,
        string $expectedState,
        ?int $expectedTotalFailedTries,
    ): void {
        // Set
        $processIdentifier = 'test-1';
        $persistedState = (object) [
            'name' => 'closed',
            'totalFailedTries' => $persistedTotalFailedTries,
            'noTriesTimestampLimit' => null,
        ];
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
            ->willReturn($persistedState);

        $stateRepository->expects($this->once())
            ->method('saveState')
            ->with(
                $processIdentifier,
                $this->anything(),
            )->willReturn(true);

        // Actions
        $cb->begin();
        $canExecute = $cb->canExecute();
        $cb->end($processExecutedAsSuccess);
        $currentState = $cb->getCurrentState();

        // Assertions
        $this->assertTrue($canExecute);
        $this->assertSame($expectedState, is_null($currentState) ?: get_class($currentState));
        $this->assertSame($expectedTotalFailedTries, $currentState?->getTotalFailedTries());
        $expectedState === ClosedState::class
            ? $this->assertNull($currentState?->getNoTriesTimestampLimit())
            : $this->assertIsInt($currentState?->getNoTriesTimestampLimit());
    }

    #[DataProvider('openedCaseDataProvider')]
    public function testShouldApplyMinimalCBCircuitIsOpened(
        bool $noTriesTimestampLimitIsExpired,
        string $expectedState,
    ): void {
        // Set
        $processIdentifier = 'test-1';
        $noTriesTimestampLimit = $noTriesTimestampLimitIsExpired
            ? strtotime('yesterday')
            : strtotime('tomorrow');
        $persistedState = (object) [
            'name' => 'opened',
            'totalFailedTries' => null,
            'noTriesTimestampLimit' => $noTriesTimestampLimit,
        ];
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
            ->willReturn($persistedState);

        if ($noTriesTimestampLimitIsExpired) {
            $stateRepository->expects($this->once())
            ->method('saveState')
            ->with(
                $processIdentifier,
                $this->equalTo((object) [
                    'name' => 'halfOpened',
                    'totalFailedTries' => null,
                    'noTriesTimestampLimit' => null,
                ]),
            )->willReturn(true);
        }

        // Actions
        $cb->begin();
        $canExecute = $cb->canExecute();
        $currentState = $cb->getCurrentState();

        // Assertions
        $this->assertSame($canExecute, $noTriesTimestampLimitIsExpired);
        $this->assertSame($expectedState, is_null($currentState) ?: get_class($currentState));
    }

    /**
     * @return array<array<bool|int>>
     */
    public static function initialCaseDataProvider(): array
    {
        return [
            'Initial status when execution was success.' => [
                'processExecutedAsSuccess' => true,
                'expectedTotalFailedTries' => 0,
            ],
            'Initial status when execution was failure.' => [
                'processExecutedAsSuccess' => false,
                'expectedTotalFailedTries' => 1,
            ],
        ];
    }

    /**
     * @return array<array<bool|int|string|null>>
     */
    public static function closedCaseDataProvider(): array
    {
        return [
            'Closed status when execution was success.' => [
                'processExecutedAsSuccess' => true,
                'persistedTotalFailedTries' => 0,
                'expectedState' => ClosedState::class,
                'expectedTotalFailedTries' => 0,
            ],
            'Closed status when execution was failure but less than failed tries limit.' => [
                'processExecutedAsSuccess' => false,
                'persistedTotalFailedTries' => 0,
                'expectedState' => ClosedState::class,
                'expectedTotalFailedTries' => 1,
            ],
            'Closed status when execution was failure and failed tries limit exceeded.' => [
                'processExecutedAsSuccess' => false,
                'persistedTotalFailedTries' => 2,
                'expectedState' => OpenedState::class,
                'expectedTotalFailedTries' => null,
            ],
        ];
    }

    /**
     * @return array<array<bool|string>>
     */
    public static function openedCaseDataProvider(): array
    {
        return [
            'Opened status when now is less than no tries timestamp limit.' => [
                'noTriesTimestampLimitIsExpired' => false,
                'expectedState' => OpenedState::class,
            ],
            'Opened status when now is more than no tries timestamp limit.' => [
                'noTriesTimestampLimitIsExpired' => true,
                'expectedState' => HalfOpenedState::class,
            ],
        ];
    }
}
