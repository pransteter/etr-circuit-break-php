<?php

namespace Pransteter;

use Pransteter\MinimalCB\Contracts\StateRepository;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\Configuration;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\State;
use Pransteter\MinimalCB\Strategy\StrategyIdentifier;
use Pransteter\MinimalCB\Strategy\StrategyProcessor;
use Pransteter\MinimalCB\Transformers\StateTransformer;
use Pransteter\MinimalCB\Validators\StateValidator;

class MinimalCB
{
    private ?State $currentState;

    private readonly StateTransformer $stateTransformer;

    private readonly StrategyProcessor $strategyProcessor;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly StateRepository $stateRepository,
    ) {
        $this->stateTransformer = new StateTransformer(
            new StateValidator(),
        );

        $this->strategyProcessor = new StrategyProcessor(
            new StrategyIdentifier($this->configuration),
        );
    }

    public function begin(): void
    {
        $this->loadCurrentState();
    }

    public function canExecute(): bool
    {
        if (
            is_null($this->currentState)
            || $this->currentState instanceof ClosedState
        ) {
            return true;
        }

        return $this->canExecuteToCheckIfComeBackToWork();
    }

    public function end(bool $executionWasSuccessful): void
    {
        $newState = $this->strategyProcessor->processStrategy(
            $this->currentState,
            $executionWasSuccessful,
        );

        $this->stateRepository->saveState(
            $this->configuration->processIdentifier,
            $this->stateTransformer->transformDTOStateToRawState($newState),
        );
    }

    private function loadCurrentState(): void
    {
        $rawState = $this->stateRepository->getState(
            $this->configuration->processIdentifier,
        );

        $this->currentState = is_null($rawState)
            ? null
            : $this->stateTransformer->transformRawStateToDTOState($rawState);
    }

    private function canExecuteToCheckIfComeBackToWork(): bool
    {
        $nextState = $this->strategyProcessor->processStrategy(
            $this->currentState,
            null
        );

        if ($nextState instanceof HalfOpenedState) {
            $this->updateCurrentState($nextState);

            return true;
        }

        return false;
    }

    private function updateCurrentState(HalfOpenedState $nextState): void
    {
        $this->stateRepository->saveState(
            $this->configuration->processIdentifier,
            $this->stateTransformer->transformDTOStateToRawState($nextState),
        );

        $this->currentState = $nextState;
    }
}
