<?php

namespace Pransteter;

use Pransteter\CircuitBreak\Contracts\StateRepository;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;
use Pransteter\CircuitBreak\Transformers\StateTransformer;
use Pransteter\CircuitBreak\Validators\StateValidator;

class CircuitBreak
{
    private readonly StateTransformer $stateTransformer;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly StateRepository $stateRepository,
    ) {
        $this->stateTransformer = new StateTransformer(
            new StateValidator(),
        );
    }
    
    public function resolveAsSuccess(): void
    {
        $this->resolve(true);
    }

    public function resolveAsFailure(): void
    {
        $this->resolve(false);
    }

    private function resolve(bool $processWasSuccessful): void
    {
        $lastPersistedState = $this->stateRepository->getState(
            $this->configuration->processIdentifier
        );

        $newState = (new StrategyProcessor(
            $this->configuration, 
            $this->stateTransformer->transformRawStateToDTOState($lastPersistedState),
        ))->processStrategy($processWasSuccessful);

        $this->stateRepository->saveState(
            $this->configuration->processIdentifier,
            $this->stateTransformer->transformDTOStateToRawState($newState),
        );
    }
}
