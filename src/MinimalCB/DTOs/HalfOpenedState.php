<?php

namespace Pransteter\MinimalCB\DTOs;

final class HalfOpenedState extends State
{
    protected const NAME = 'halfOpened';

    protected function getName(): string
    {
        return self::NAME;
    }
}
