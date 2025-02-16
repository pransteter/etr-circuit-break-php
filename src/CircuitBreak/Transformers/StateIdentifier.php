<?php

namespace Pransteter\CircuitBreak\Transformers;

use Pransteter\CircuitBreak\DTOs\ClosedState;
use Pransteter\CircuitBreak\DTOs\HalfOpenedState;
use Pransteter\CircuitBreak\DTOs\OpenedState;
use stdClass;

class StateIdentifier
{
    static public function identifyStateClassName(string $stateName): string
    {
        switch ($stateName) {
            case 'Opened':
                return OpenedState::class;
            case 'Closed':
                return ClosedState::class;
            case 'HalfOpened':
                return HalfOpenedState::class;
            default:
                throw new \Exception('Invalid state name.');
        }
    }
}
