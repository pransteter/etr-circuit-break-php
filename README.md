# Minimal Circuit Breaker

### Usage:

```php
// Configure

// - 1: Create a repository to persist and get the state:
class MyAppCBStateRepository implements \Pransteter\MinimalCB\Contracts\StateRepository
{
    public function saveState(string $index, \stdClass $state): bool
    {
        // code to save a state.
    }
    public function getState(string $index): ?\stdClass
    {
        // code to find a state.
    }
}

$repository = new MyAppCBStateRepository();

// - 2: Create a object of Configuration to set CB settings:
$configuration = new \Pransteter\MinimalCB\DTOs\Configuration(
    processIdentifier: 'get-weather-data-from-api',
    failedTriesLimit: 5,
    secondsToStayOpened: 300,
);

// Initialize MinimalCB:
$cb = new MinimalCB(
    $configuration,
    $repository,
)->begin();

// Check if can be executed
if($cb->canExecute()) {
    // procede with execution and get the result.
    try {
        // - In case of success
        $cb->end(true);
    } catch (\Throwable $e) {
        // - executed with failure
        $cb->end(false);
    }
}
```
