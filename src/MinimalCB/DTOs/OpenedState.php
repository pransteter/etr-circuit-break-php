<?php

namespace Pransteter\MinimalCB\DTOs;

final class OpenedState extends State
{
    protected const NAME = 'opened';

    protected function getName(): string
    {
        return self::NAME;
    }
}
