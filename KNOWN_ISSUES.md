# Known Issues


---

## FHIRPath Specification Conformance Tests

Test suite: `composer test-ai-fhirpath-spec`
Source: `vendor/fhir/fhir-test-cases/r4/fhirpath/tests-fhir-r4.xml`
Current baseline (2026-03-09): **923 passing / 1 skipped / 948 total**

The following test groups are known non-conformances or intentional deferrals.

---

### Quantity calendar unit comparisons

**Tests:** `testStringQuantityMonthLiteralToQuantity`, `testStringQuantityYearLiteralToQuantity`

`'1 \'mo\''.toQuantity() = 1 month` should return empty because calendar units (`month`, `year`) are not definitively comparable to UCUM unit strings. The result depends on whether `ToQuantityFunction` accepts UCUM `'mo'`/`'a'` strings and whether the comparison correctly returns empty for calendar-unit ambiguity.

---

### Pre-existing unit test failures (NOT caused by FHIRPath work)

The following failures exist in the main test suites and are unrelated to FHIRPath spec conformance:

- `QuantityAndStringFunctionTest::testToStringFromQuantityArray` — integer quantity formats as `'10 'mg''` not `'10.0 'mg''`
- `SerializationPerformanceTest` — flaky timing-based test
