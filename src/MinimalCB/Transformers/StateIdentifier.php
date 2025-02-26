<?php

namespace Pransteter\MinimalCB\Transformers;

use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;
use Pransteter\MinimalCB\DTOs\OpenedState;
use stdClass;

class StateIdentifier
{
    public static function identifyStateClassName(string $stateName): string
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
