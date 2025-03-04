<?php

namespace Pransteter\MinimalCB\DTOs;

final class ClosedState extends State
{
    private const NAME = 'closed';

    public static function getName(): string
    {
        return self::NAME;
    }
}
