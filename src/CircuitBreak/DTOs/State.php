<?php

namespace Pransteter\CircuitBreak\DTOs;

use stdClass;

abstract class State
{   
    private readonly string $name;

    abstract protected function getName(): string;

    public function __construct(
        private readonly int $totalTries,
        private readonly int $triesLimit,
        private readonly int $noTriesTimestampLimit,
    ) {
        $this->name = $this->getName();
    }

    public function __toStdClass(): stdClass
    {
        return new stdClass(
            name: $this->name,
            totalTries: $this->totalTries,
            triesLimit: $this->triesLimit,
            noTriesTimestampLimit: $this->noTriesTimestampLimit,
        );
    }
}
