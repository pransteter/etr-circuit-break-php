<?php

namespace Pransteter\CircuitBreak\Validators;

use stdClass;

class StateValidator
{
    private array $errorsBag = [];

    public function isValid(stdClass $rawState): bool
    {
        return true;
    }

    public function getErrors(): string
    {
        return json_encode($this->errorsBag);
    }
}