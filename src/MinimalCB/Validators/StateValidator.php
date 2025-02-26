<?php

namespace Pransteter\MinimalCB\Validators;

use stdClass;

class StateValidator
{
    /**
     * @var array<string>
     */
    private array $errorsBag = [];

    public function isValid(stdClass $rawState): bool
    {
        return true;
    }

    public function getErrors(): string
    {
        $jsonErrors = json_encode($this->errorsBag);

        return $jsonErrors === false ? '' : $jsonErrors;
    }
}
