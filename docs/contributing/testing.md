---
description: Test structure, running tests, and testing utilities.
icon: vial
---

# Testing

<!-- TODO: migrate "Structure", "Running Tests", "Test Utilities", "Conventions" from tests/README.md -->

## Running tests

```bash
composer test          # all tests
composer test-unit     # unit suite
```

## Test utilities

* `TestCase` — base class with FHIR-specific assertions
* `FHIRTestDataGenerator` — property-based test data (Eris)

<!-- MIGRATION SOURCE: tests/README.md -->
