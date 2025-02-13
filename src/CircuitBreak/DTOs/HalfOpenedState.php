<?php

namespace Pransteter\CircuitBreak\DTOs;

final class HalfOpenedState extends State
{
    private const NAME = 'halfOpened';
    
    protected function getName(): string
    {
        return self::NAME;
    }
}
