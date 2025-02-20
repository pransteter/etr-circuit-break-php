<?php

namespace Pransteter\CircuitBreak\DTOs;

use stdClass;

abstract class State
{   
    private readonly string $name;

    abstract protected function getName(): string;

    public function __construct(
        private readonly ?int $totalFailedTries,
        private readonly ?int $noTriesTimestampLimit,
    ) {
        $this->name = $this->getName();
    }

    public function __toStdClass(): stdClass
    {
        return new stdClass(
            name: $this->name,
            totalFailedTries: $this->totalFailedTries,
            noTriesTimestampLimit: $this->noTriesTimestampLimit,
        );
    }

    public function getTotalFailedTries(): ?int
    {
        return $this->totalFailedTries;
    }

    public function getNoTriesTimestampLimit(): ?int
    {
        return $this->noTriesTimestampLimit;
    }
}
