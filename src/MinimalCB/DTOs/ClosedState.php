<?php

namespace Pransteter\MinimalCB\DTOs;

final class ClosedState extends State
{
    protected const NAME = 'closed';

    protected function getName(): string
    {
        return self::NAME;
    }
}
