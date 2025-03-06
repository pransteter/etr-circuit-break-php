<?php

namespace Pransteter\MinimalCB\DTOs;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(State::class)]
class StateTest extends TestCase
{
    public function testShouldCheckStateProperties(): void
    {
        // Set
        $totalFailedTries = 1;
        $noTriesTimestampLimit = 2;

        $state = new class (
            totalFailedTries: $totalFailedTries,
            noTriesTimestampLimit: $noTriesTimestampLimit,
        ) extends State {
            public static function getName(): string
            {
                return 'test';
            }
        };

        // Actions
        $nameFromState = $state->getName();
        $totalFailedTriesFromState = $state->getTotalFailedTries();
        $noTriesTimestampLimitFromState = $state->getNoTriesTimestampLimit();

        $stateAsStdClass = $state->__toStdClass();

        // Assertions
        $this->assertSame('test', $nameFromState);
        $this->assertSame($totalFailedTries, $totalFailedTriesFromState);
        $this->assertSame($noTriesTimestampLimit, $noTriesTimestampLimitFromState);
        $this->assertEquals(
            (object) [
                'name' => 'test',
                'totalFailedTries' => $totalFailedTries,
                'noTriesTimestampLimit' => $noTriesTimestampLimit,
            ],
            $stateAsStdClass,
        );
    }
}
