<?php

namespace Pransteter\CircuitBreak\DTOs;

final class OpenedState extends State
{
    private const NAME = 'opened';
    
    protected function getName(): string
    {
        return self::NAME;
    }
}
