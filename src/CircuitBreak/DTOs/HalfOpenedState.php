<?php

namespace Pransteter\CircuitBreak\DTOs;

final class HalfOpenedState extends State
{
    protected const NAME = 'halfOpened';
    
    protected function getName(): string
    {
        return self::NAME;
    }
}
