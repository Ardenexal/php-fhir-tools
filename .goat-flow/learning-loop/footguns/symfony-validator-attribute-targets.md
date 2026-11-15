---
category: symfony-validator-attribute-targets
last_reviewed: 2026-08-31
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

## Footgun: getAttributes() does not see inherited attributes, so a derived profile loses its parent's rules

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M11, twice in one milestone)

`ReflectionClass::getAttributes()` reports only what *that* class declares. It does not walk parents.
Generated FHIR profile classes form a real inheritance chain — `ObservationBpProfile extends
ObservationVitalsignsProfile extends ObservationResource` — and a derived profile inherits its parent's
constraints without re-declaring them. So reading attributes off the instance class alone silently drops
every rule the parent owns.

This bit twice in one milestone, in two different validators:

- `FHIRSliceConstraintValidator::collectSliceConstraints()` and `readSlicingRules()` read
  `new ReflectionClass($value)` only. The `VSCat` slice on `category` is declared on the *vitalsigns*
  parent, so a `bp` instance never saw it and a required slice was never checked.
- Constraint **groups** have the same shape: a parent's `#[FHIRProfileConstraint]`s carry the *parent's*
  canonical URL as their group, so activating only the URL the instance names leaves every inherited
  cardinality rule inert. Four of five target findings sat on the parent.

Both failures are silent — no error, no violation, just fewer findings than there should be.

**Fix:** walk the chain explicitly wherever profile metadata is read.

```php
for ($class = $refl; $class !== false; $class = $class->getParentClass()) {
    foreach ($class->getAttributes(FHIRSliceConstraint::class) as $attr) { /* ... */ }
}
```

For groups, collect each ancestor's `#[FHIRProfile]::profileUrl` and activate all of them, not just the
leaf. Proven by mutation: reading only the instance class drops `VSCat` from `obs-quantity-us`, which the
reference validator reports.

## Footgun: per-instance dedup state in a ConstraintValidator is always empty

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M11)

A `ConstraintValidatorFactory` is free to return a **fresh validator instance for every constraint it is
asked about**, and the common implementations do — including this project's own
`OracleValidationServiceFactory`, whose `getInstance()` is a `match` full of `new ...Validator(...)`.

Any state a validator keeps on `$this` to avoid repeating work therefore starts empty on every call.
`FHIRSliceConstraintValidator` held a per-instance `\WeakMap $processedKeys` meant to process each
(property, group) once; because a new validator was constructed per constraint, it never deduped
anything. `bp` declares two slices on `component`, so the property was matched twice and every finding
was reported twice over. The bug was invisible for as long as profile constraint groups were never
activated, and surfaced the moment they were.

**Fix:** make cross-constraint state `static`, keyed on the `ExecutionContextInterface` so separate
`validate()` runs stay isolated and entries fall away with their context:

```php
private static ?\WeakMap $processedKeys = null;
```

**Smell to watch for:** any `$this->seen`, `$this->processed`, or memoisation cache on a ConstraintValidator.
Either it is per-call state that belongs in a local, or it is cross-constraint state that must be static
and context-keyed. There is no correct middle.

## Footgun: flattening a repeating element turns per-occurrence cardinality into a false positive

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M06, caught by measurement)

A FHIR element path that crosses a repeating element names one rule per occurrence of the *parent*, not
one rule over everything the path reaches. `Observation.component.value[x]` with max 1 means each
component carries at most one value. A blood pressure has a systolic and a diastolic component and holds
two values in total, and breaks nothing.

Reading that path into a single flat list and sizing it against the max reports a conforming document as
invalid. In M06 it turned R5 `bp` — a valid vital-signs example the reference validator passes without a
single error — into an `ABOVE` case with three false positives.

**Fix:** walk the path to the *parents* of the final segment, read the final segment once per parent, and
apply the rule to each group:

```php
$segments = explode('.', $path);
$leaf     = array_pop($segments);
// walk $segments to a set of parents, then one occurrence list per parent
```

**Two adjacent traps found while getting the rest of the semantics right:**

- **A null single value is an absent element; a null entry inside a repeating element is not.** Zero
  occurrences against one. Treating them alike first broke an `All` constraint that is meant to receive
  the null it rejects, then silently satisfied every `min => 1` on an absent element — which looks like
  a *pass*, not a failure, and cost three real findings before a probe caught it.
- **Only cardinality rules want the occurrence list.** Everything else — `All`, fixed values, patterns —
  inspects the element as the model holds it, and hands-back a list where an object was expected.
  Return both readings and let the rule pick.

**Why it is worth a note:** none of these is visible in a class-level pass/fail. The corpus reported
`EQUAL` throughout one of them, because two missing findings cancelled two false positives. Only reading
our output against the reference outcome finding by finding surfaced it. Relates to
[[getAttributes() does not see inherited attributes]].

