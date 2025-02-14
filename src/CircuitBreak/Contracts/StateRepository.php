<?php

namespace Pransteter\CircuitBreak\Contracts;

interface StateRepository
{
    public function saveState(string $index, \stdClass $state): bool;
    public function getState(string $index): \stdClass;
}
