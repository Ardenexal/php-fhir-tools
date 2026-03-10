# Known Issues

## XML-to-JSON Serialization: CodeableConcept.coding Array Bug

**Affected Component:** Serialization  
**Status:** Known bug — documented with skipped test  
**Test:** `BundleXmlToJsonConversionTest::testIdentifierTypeCodingIsArrayNotObject`

When deserializing FHIR XML and re-serializing to JSON, CodeableConcept fields with `coding` arrays are incorrectly serialized as objects with `@value` wrappers instead of arrays.

**Expected JSON:**
```json
"type": {
  "coding": [{
    "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
    "code": "MR"
  }]
}
```

**Actual (incorrect) output:**
```json
"type": {
  "coding": {
    "system": { "@value": "http://terminology.hl7.org/CodeSystem/v2-0203" },
    "code": { "@value": "MR" }
  }
}
```

**Root cause:** XML internal representation (`@value` wrappers) leaking into JSON serialization, and array detection failing for single-element CodeableConcept.coding arrays.

**Workaround:** Direct JSON-to-JSON or XML-to-XML conversions are unaffected. Only impacts XML→Object→JSON round-trips.

**Fix plan:** Requires normalizer improvements to:
1. Detect array properties correctly even when XML has single elements
2. Strip XML-specific metadata (`@value` wrappers) during normalization

---

## FHIRPath Specification Conformance Tests

Test suite: `composer test-ai-fhirpath-spec`
Source: `vendor/fhir/fhir-test-cases/r4/fhirpath/tests-fhir-r4.xml`
Current baseline (2026-03-09): **923 passing / 1 skipped / 948 total**

The following test groups are known non-conformances or intentional deferrals.

---

### Strict-mode semantic validation — deferred

**Tests:** `testSimpleFail`, `testSimpleWithWrongContext`, `testPolymorphismB`, `testPolymorphismAsB`, `testDollarOrderNotAllowed`

These tests carry `mode="strict"` on the `<test>` element and `invalid="semantic"` on the `<expression>`. A fully conformant strict-mode evaluator should throw a semantic error when:
- A non-existent property is navigated (`name.given1`)
- A resource type prefix that does not match is used (`Encounter.name` on a Patient)
- A concrete polymorphic variant name is used directly (`Observation.valueQuantity`)
- A property name is accessed on a type it does not belong to (`(Observation.value as Period).unit`)
- An ordered function (`skip()`) is applied to an unordered collection (`children()`)

**Current behaviour:** The evaluator returns an empty collection without throwing.

**Status:** Skipped (marked as `markTestSkipped`) — strict-mode semantic validation requires a full FHIR structure definition traversal and is not yet implemented.

---

### `conformsTo()` — complex profile conformance

**Test:** `testConformsToStructureDefinitionComplex`

The `conformsTo()` function validates a resource against a profile URL. The built-in implementation requires a `conformsToValidator` callable to be set on the evaluator (via `FHIRPathEvaluator::setConformsToValidator()`). The test exercises a complex nested extension scenario that the default no-op validator does not handle.

**Status:** Error — exception thrown because no validator is configured for the complex case.

---

### `as()` operator with non-matching types

**Test:** `testFHIRPathAsFunction11` (`Patient.gender.as(string)` → empty)

The `as` operator uses **strict** type identity: `code as string` returns empty even though `code is string` is true (via type hierarchy). This is correct FHIRPath semantics per §8.4.3. The evaluator was previously using type coercion (`castToType`) which incorrectly cast `code` to `string`.

---

### FHIR type inheritance in `is` checks

**Tests:** `testFHIRPathIsFunction8` (`is Age`), `testFHIRPathIsFunction9` (`is Quantity`)

`Age extends Quantity` in the FHIR data model and in the generated PHP model classes. The `is` operator must walk the PHP class hierarchy to determine that an `Age` value satisfies `is Quantity`. This requires walking the `FHIRComplexType` attribute chain on parent classes.

---

### `ofType(HumanName)` filtering

**Test:** `testFHIRPathAsFunction22` (`Patient.name.ofType(HumanName).use`)

The `ofType()` function uses strict type identity. Deserialized `Patient.name` items are `HumanName` PHP objects. For `ofType` to recognise them it must correctly infer the FHIR type `'HumanName'` from the `#[FHIRComplexType(typeName: 'HumanName')]` attribute on the class.

---

---

### Quantity calendar unit comparisons

**Tests:** `testStringQuantityMonthLiteralToQuantity`, `testStringQuantityYearLiteralToQuantity`

`'1 \'mo\''.toQuantity() = 1 month` should return empty because calendar units (`month`, `year`) are not definitively comparable to UCUM unit strings. The result depends on whether `ToQuantityFunction` accepts UCUM `'mo'`/`'a'` strings and whether the comparison correctly returns empty for calendar-unit ambiguity.

---

### Pre-existing unit test failures (NOT caused by FHIRPath work)

The following failures exist in the main test suites and are unrelated to FHIRPath spec conformance:

- `CollectionTest::testCollectionEqualityOrderIndependent`, `testCollectionNotEquals`, `testMixedTypesInCollections`, `testCollectionNotEqualsSameValues` — incorrect test expectations (FHIRPath `=` is positional, not order-independent)
- `QuantityAndStringFunctionTest::testToStringFromQuantityArray` — integer quantity formats as `'10 'mg''` not `'10.0 'mg''`
- `SerializationPerformanceTest` — flaky timing-based test
