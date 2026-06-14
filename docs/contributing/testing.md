---
description: Test structure, running tests, and testing utilities.
icon: vial
---

# Testing

Tests live in the root `tests/` directory (namespace `Ardenexal\FHIRTools\Tests\`) and in each
component's own `tests/` directory (for example `src/Component/Serialization/tests/`).

## Structure

The root `tests/` directory contains:

```
tests/
├── Integration/      # cross-component integration tests
├── Utilities/        # shared test base class and data generators
├── bootstrap.php     # PHPUnit bootstrap
└── README.md
```

Most unit tests live alongside their component under `src/Component/<Name>/tests/`. The PHPUnit
test suites are declared in `phpunit.dist.xml`; the main ones are `unit`, `integration`,
`fhirpath-spec`, `questionnaire-spec`, and `brianpos-questionnaire-spec`, plus per-component
`integration-*` suites.

## Running tests

```bash
composer test               # all tests (PHPUnit, --testdox)
composer test-unit          # unit suite (paratest)
composer test-integration   # integration suite
composer test-coverage      # HTML + Clover coverage report
```

Per-component suites:

```bash
composer test:bundle
composer test:codegen
composer test:serialization
composer test:fhir-path
composer test:validation
```

{% hint style="info" %}
**`-ai` variants.** Compact-output runners exist for agent workflows: `composer test-ai`,
`composer test-ai-unit`, `composer test-ai-integration`, `composer test-ai-fhir`,
`composer test-ai-fhirpath-spec`, and the questionnaire-spec variants. Per repo policy these are
preferred when running under an agent harness.
{% endhint %}

## Test utilities

Both utilities live in the `Ardenexal\FHIRTools\Tests\Utilities` namespace
(`tests/Utilities/`).

### `TestCase`

`Ardenexal\FHIRTools\Tests\Utilities\TestCase` is an `abstract` base class extending PHPUnit's
`TestCase` with FHIR-specific assertions:

```php
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

final class MyTest extends TestCase
{
    public function testSomething(): void
    {
        self::assertValidPhpCode($generatedCode);
        // ...
    }
}
```

### `FHIRTestDataGenerator`

`Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator` provides property-based test data
generators built on [Eris](https://github.com/giorgiosironi/eris):

```php
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;

$resourceType = FHIRTestDataGenerator::fhirResourceType();
```

## Conventions

* Use `self::assert*()` methods (not `$this->assert*()`)
* All test methods must have `void` return types
* Clean up temporary files in `tearDown()`
* Place shared fixtures under a `Fixtures/` directory and reuse them for complex test data
* All new code must have meaningful tests
