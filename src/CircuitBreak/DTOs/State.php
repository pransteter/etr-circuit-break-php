<?php

namespace Pransteter\CircuitBreak\DTOs;

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

    public function __toJson(): string
    {
        return json_encode(get_object_vars($this));
    }
}
