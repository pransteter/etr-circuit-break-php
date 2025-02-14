<?php

namespace Pransteter\CircuitBreak\DTOs;

final class Configuration
{
    public function __construct(
        public readonly string $processIdentifier,
        public readonly int $totalTries,
        public readonly int $triesLimit,
        public readonly int $noTriesTimestampLimit,
    ) {}
}
