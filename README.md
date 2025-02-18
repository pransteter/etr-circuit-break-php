# etr-circuit-break-php

### Usage:

```
// Initializing
$cb = new CircuitBreak(
    $configuration,
    $repository,
)->begin();

// Check if can be executed
if($cb->canExecute()) {
    // procede with execution and get the result.
}

// Finish the process telling the result to end method.

// executed with success
$cb->end(true);

// executed with failure
$cb->end(false);
```