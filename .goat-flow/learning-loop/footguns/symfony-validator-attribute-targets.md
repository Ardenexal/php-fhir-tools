---
category: symfony-validator-attribute-targets
last_reviewed: 2026-05-29
---

# Footguns: Symfony Validator + PHP Attribute Targets

## Footgun: Custom constraint attribute missing TARGET_PROPERTY silently produces no violations

**Status:** active | **Created:** 2026-05-20 | **Evidence:** OBSERVED (M01 spike)

Symfony Validator's `AttributeLoader` reads constraint metadata via `ReflectionProperty::getAttributes()`,
not `ReflectionParameter::getAttributes()`. PHP promoted constructor parameters expose their attributes on
**both** reflectors — but only when the attribute's `$flags` includes `\Attribute::TARGET_PROPERTY`.

If a custom constraint attribute is declared with only `TARGET_PARAMETER`, Symfony Validator will never see
it on the property reflector. The model validates without error, no violation is raised, and no exception
is thrown. This failure mode is completely silent.

**Correct declaration for any constraint attribute used with Symfony Validator on generated models:**

```php
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRMyConstraint { ... }
```

**Evidence:** `src/Component/CodeGeneration/tests/Unit/Generator/ConstraintEmissionSpikeTest.php` — `testCustomAttributeRoundTripAndTargetPropertyFlag` (search: `"TARGET_PROPERTY flag must be non-zero"`) asserts
`($flags & \Attribute::TARGET_PROPERTY) !== 0` on `FHIRValueSetBindingStub`. M01 kill criterion NOT fired.

**Applies to:** all custom FHIR constraint attributes in `src/Component/Validation/src/Constraint/` and
`src/Component/Metadata/src/Attribute/` that will be read by Symfony Validator.

## Footgun: enableAttributeMapping() is mandatory — createValidator() alone reads nothing

**Status:** active | **Created:** 2026-05-20 | **Evidence:** OBSERVED (M01 spike)

`Validation::createValidator()` does NOT read PHP attributes. The correct bootstrap is:

```php
$validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
```

Without `enableAttributeMapping()`, all attribute-based constraints are silently ignored.

**Evidence:** `src/Component/CodeGeneration/tests/Unit/Generator/ConstraintEmissionSpikeTest.php` — `testSymfonyValidatorReadsConstraintsFromNetteGeneratedPromotedParams` (search: `"createValidatorBuilder()->enableAttributeMapping()"`) confirms violations only appear when `enableAttributeMapping()` is called.
