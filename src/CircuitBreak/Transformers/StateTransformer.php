<?php

namespace Pransteter\CircuitBreak\Transformers;

use Exception;
use Pransteter\CircuitBreak\DTOs\State;
use Pransteter\CircuitBreak\Validators\StateValidator;
use stdClass;

class StateTransformer
{
    public function __construct(
        private readonly StateValidator $stateValidator,
    ) {
    }

    public function transformRawStateToDTOState(stdClass $rawState): State
    {
        if (!$this->stateValidator->isValid($rawState)) {
            throw new Exception(
                sprintf(
                    'There are one or more invalid attributes in the state. Look: %s',
                    $this->stateValidator->getErrors(),
                ),
            );
        }

        $stateClassName = StateIdentifier::identifyStateClassName(
            is_string($rawState->name)
            ? $rawState->name
            : '',
        );

        $newState = new $stateClassName(
            totalFailedTries: $rawState->totalFailedTries,
            noTriesTimestampLimit: $rawState->noTriesTimestampLimit,
        );

        if (!($newState instanceof State)) {
            throw new Exception('Invalid state.');
        }

        return $newState;
    }

    public function transformDTOStateToRawState(State $state): stdClass
    {
        return $state->__toStdClass();
    }
}
