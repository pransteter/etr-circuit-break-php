<?php

namespace Pransteter\MinimalCB\Transformers;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pransteter\MinimalCB\DTOs\OpenedState;
use Pransteter\MinimalCB\DTOs\ClosedState;
use Pransteter\MinimalCB\DTOs\HalfOpenedState;

#[CoversClass(StateIdentifier::class)]
#[UsesClass(ClosedState::class)]
#[UsesClass(OpenedState::class)]
#[UsesClass(HalfOpenedState::class)]
class StateIdentifierTest extends TestCase
{
    public function testShouldReturnCorrectStateClassName(): void
    {
        // Set
        $stateIdentifier = new StateIdentifier();

        // Actions
        $openedClass = $stateIdentifier->identifyStateClassName('opened');
        $closedClass = $stateIdentifier->identifyStateClassName('closed');
        $halfOpenedClass = $stateIdentifier->identifyStateClassName('halfOpened');

        // Assertions
        $this->assertEquals(OpenedState::class, $openedClass);
        $this->assertEquals(ClosedState::class, $closedClass);
        $this->assertEquals(HalfOpenedState::class, $halfOpenedClass);
    }

    public function testShouldThrowAnExceptionWhenNameInputIsInvalid(): void
    {
        // Set
        $stateIdentifier = new StateIdentifier();

        // Expectations
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid state name.');

        // Actions
        $openedClass = $stateIdentifier->identifyStateClassName('unknown');
    }
}
