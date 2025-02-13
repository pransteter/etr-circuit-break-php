<?php

namespace Pransteter\CircuitBreak\DTOs;

final class ClosedState extends State
{
    private const NAME = 'closed';
    
    protected function getName(): string
    {
        return self::NAME;
    }
}
