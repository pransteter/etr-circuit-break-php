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
            case 'opened':
                return OpenedState::class;
            case 'closed':
                return ClosedState::class;
            case 'halfOpened':
                return HalfOpenedState::class;
            default:
                throw new \Exception('Invalid state name.');
        }
    }
}
