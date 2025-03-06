<?php

namespace Pransteter\MinimalCB\Validators;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(StateValidator::class)]
class StateValidatorTest extends TestCase
{
    public function testShouldValidateStateAsValid(): void
    {
        // Set
        $validator = new StateValidator();

        // Actions
        $result = $validator->isValid(new stdClass());
        $errors = $validator->getErrors();

        // Assertions
        $this->assertTrue($result);
        $this->assertEmpty(json_decode($errors));
    }
}
