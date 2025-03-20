<?php

namespace Pransteter\MinimalCB\Transformers;

use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;

class StateIdentifier
{
    public static function identifyStateClassName(string $stateName): string
    {
        switch ($stateName) {
            case OpenedState::getName():
                return OpenedState::class;
            case ClosedState::getName():
                return ClosedState::class;
            case HalfOpenedState::getName():
                return HalfOpenedState::class;
            default:
                throw new \Exception('Invalid state name.');
        }
    }
}
