<?php

namespace Pransteter\MinimalCB\DTOs;

final class HalfOpenedState extends State
{
    private const NAME = 'halfOpened';

    public static function getName(): string
    {
        return self::NAME;
    }
}
