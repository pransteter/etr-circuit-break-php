<?php

namespace Pransteter;

use Pransteter\CircuitBreak\Contracts\StateRepository;
use Pransteter\CircuitBreak\DTOs\Configuration;
use Pransteter\CircuitBreak\Strategy\StrategyProcessor;

class CircuitBreak
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly StateRepository $stateRepository,
    ) {
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
        $newState = (new StrategyProcessor($this->configuration, $lastPersistedState)) // transform stdClass in State
            ->processStrategy($processWasSuccessful);

        $this->stateRepository->saveState(
            $this->configuration->processIdentifier,
            $newState, // transform State in stdClass
        );
    }
}
