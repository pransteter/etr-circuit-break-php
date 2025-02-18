<?php

namespace Pransteter\CircuitBreak\DTOs;

use stdClass;

abstract class State
{   
    private readonly string $name;

    abstract protected function getName(): string;

    public function __construct(
        private readonly ?int $totalTries,
        private readonly ?int $noTriesTimestampLimit,
    ) {
        $this->name = $this->getName();
    }

    public function __toStdClass(): stdClass
    {
        return new stdClass(
            name: $this->name,
            totalTries: $this->totalTries,
            noTriesTimestampLimit: $this->noTriesTimestampLimit,
        );
    }

    public function getTotalTries(): ?int
    {
        return $this->totalTries;
    }
}
