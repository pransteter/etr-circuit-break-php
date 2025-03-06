<?php

namespace Pransteter\MinimalCB\DTOs;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Configuration::class)]
class ConfigurationTest extends TestCase
{
    public function testConfigurationInitialization(): void
    {
        $processIdentifier = 'test-process';
        $failedTriesLimit = 3;
        $secondsToStayOpened = 300;

        $configuration = new Configuration(
            processIdentifier: $processIdentifier,
            failedTriesLimit: $failedTriesLimit,
            secondsToStayOpened: $secondsToStayOpened,
        );

        $this->assertSame($processIdentifier, $configuration->processIdentifier);
        $this->assertSame($failedTriesLimit, $configuration->failedTriesLimit);
        $this->assertSame($secondsToStayOpened, $configuration->secondsToStayOpened);
    }
}
