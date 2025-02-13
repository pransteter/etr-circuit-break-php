<?php

namespace Pransteter\CircuitBreak\Contracts;

interface HasCircuitBreak
{
    public function getProcessName(): string;
}
