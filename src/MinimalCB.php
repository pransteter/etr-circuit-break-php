<?php

namespace Pransteter;

use Exception;
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

    private ?bool $canExecute = null;

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
        if (!is_null($this->canExecute)) {
            return $this->canExecute;
        }

        $this->canExecute = $this->checkCanExecute();

        return $this->canExecute;
    }

    private function checkCanExecute(): bool
    {
        if (
            is_null($this->currentState)
            || $this->currentState instanceof ClosedState
        ) {
            return true;
        }

        if ($this->currentState instanceof HalfOpenedState) {
            return false;
        }

        return $this->canExecuteToCheckIfComeBackToWork();
    }

    public function end(bool $executionWasSuccessful): void
    {
        if (!$this->canExecute) {
            throw new Exception('Process can not be executed.');
        }

        $newState = $this->strategyProcessor->processStrategy(
            $this->currentState,
            $executionWasSuccessful,
        );

        $this->currentState = $newState;

        $this->stateRepository->saveState(
            $this->configuration->processIdentifier,
            $this->stateTransformer->transformDTOStateToRawState($newState),
        );

        $this->canExecute = null;
    }

    public function getCurrentState(): ?State
    {
        return $this->currentState;
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
