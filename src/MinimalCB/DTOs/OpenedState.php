<?php

namespace Pransteter\MinimalCB\DTOs;

final class OpenedState extends State
{
    private const NAME = 'opened';

    public static function getName(): string
    {
        return self::NAME;
    }
}
