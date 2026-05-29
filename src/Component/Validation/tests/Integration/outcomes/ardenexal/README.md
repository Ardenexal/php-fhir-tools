# Ardenexal Outcome Files

Baseline outcome files capturing the actual FHIRValidationService output for each R4 test case.

## Format

```json
{
  "errorCount": 0,
  "warningCount": 0,
  "infoCount": 0
}
```

Counts exclude known-gap violations (documented in `FHIRValidatorSpecificationTest::isKnownGap()`):
- Missing enum class for required bindings
- `dom-3` contained-resource back-reference false positives
- `sdf-19` StructureDefinition type-code false positives
- `#[NotBlank]` rejecting boolean `false` on generated `?bool` properties

## Naming

`R4.<test-name>-base.json` — matches the Java outcome naming convention.

## Seeding

Run `php src/Component/Validation/tests/Integration/seed-outcomes.php` to regenerate all files.
Update individual files when a known gap is fixed and the expected count changes.
