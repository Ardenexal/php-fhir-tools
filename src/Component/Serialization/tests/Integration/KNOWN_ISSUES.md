# Serialization Specification Test — Known Issues

Updated: 2026-02-22
Test suite: `SerializationSpecificationTest` (fhir/fhir-test-cases `r4/examples/`)
PHPUnit: 12.5.11 · PHP: 8.3.8

---

## Summary

| Metric | Count |
|---|---|
| Total example files | 82 |
| JSON files | 72 |
| XML files | 10 |
| **Passed** | **82** |
| **Skipped** (deserialization not yet supported) | **0** |
| **Failed** (deserialize ok → serialize/re-deserialize broke) | **0** |

**All 82 specification tests pass (100%).** The round-trip identity test
(preserve `resourceType` and `id`) succeeds for every example file.

---

## Previously Fixed Issues

### Pattern 1 — CodePrimitive subclass denormalization (was: 68 JSON files)

`NarrativeStatusType`, `BundleTypeType`, `MimeTypesType`, and all other
`CodePrimitive` subclasses are now correctly denormalized. The
`FHIRPrimitiveTypeNormalizer` walks the parent class hierarchy to find the
`FHIRPrimitive` attribute, so generated "Type" wrapper classes (which extend
`CodePrimitive`) are handled without bespoke registration.

### Pattern 2 — array-typed property denormalization (was: 2 JSON files)

Bare `array` typed properties (e.g. on `Questionnaire`, `Parameters`) are now
passed through as-is instead of being routed to the denormalizer chain, which
prevented a "no normalizer for array" error.

### Pattern 3 — XML deserialization (was: 10 XML files)

All ten XML example files now deserialize and re-serialize successfully. Fixes
applied:

- `FHIRResourceNormalizer::supportsDenormalization()` accepts `xml` format.
- `FHIRResourceNormalizer::denormalizeFromXML()` reads FHIR `@value` attributes
  (XmlEncoder's `['@value' => 'x', '#' => '']` pattern) and unwraps scalars.
- Single-element XML arrays (XmlEncoder collapses them to scalars) are
  re-wrapped in `[]` when the target property is `array`-typed.
- XML comments (`#comment`) and text-node artifacts (`#`) from XmlEncoder are
  stripped from stored array-typed property values to prevent re-emission errors.

### Pattern 4 — DateTimeInterface primitive handling

`DateTimePrimitive` and `InstantPrimitive` declare `$value: ?DateTimeInterface`.
Raw strings are now converted to `DateTimeImmutable` during denormalization, and
`DateTimeInterface` values are formatted to ISO 8601 strings during normalization
(across `FHIRPrimitiveTypeNormalizer`, `FHIRResourceNormalizer`,
`FHIRComplexTypeNormalizer`, and `FHIRBackboneElementNormalizer`).

---

## Remaining Architectural Concerns

These do not currently cause test failures but represent known limitations:

### Partial FHIR dates lose precision in round-trips

FHIR `dateTime` allows partial values: `"2015"`, `"2015-02"`, `"2015-02-07"`.
`DateTimePrimitive::$value` is `?DateTimeInterface`, so these are stored as
`DateTimeImmutable` with defaults for the omitted components (midnight, January,
1st). On re-serialization, `format(DateTimeInterface::ATOM)` produces a full
timestamp (e.g. `"2015-02-07T00:00:00+00:00"`) instead of the original partial
string.

The round-trip identity test (resourceType + id only) passes, but field-level
round-trip fidelity for partial dates is not guaranteed.

**Fix direction:** Change the code generator to emit `?string` for `dateTime`
and `date` FHIR primitives, matching the FHIR specification's allowance for
partial dates. This is a model-generation concern, not a serialization concern.

### `FHIRTypeResolverTest` unit test expectations are stale

Two unit tests in `FHIRTypeResolverTest` assert that the resolver returns short
class names like `'FHIRPatient'` rather than FQCNs. The resolver now correctly
returns FQCNs. These test assertions should be updated to match the new
behavior.

### Union-typed properties receive raw data in XML

Properties typed as `StringPrimitive|string|null` (a union type) return `null`
from `getPropertyType()` since `ReflectionUnionType` is not handled. The
`unwrapXmlValue()` fallback correctly extracts the scalar, but the value is
assigned to the union-typed property without going through the normalizer chain.
This works in practice for simple string union types but may fail for complex
unions.

---

## Full file-by-file results

| # | File | Format | Result |
|---|---|---|---|
| 1 | account-example.json | JSON | **Pass** |
| 2 | allergyintolerance-example.json | JSON | **Pass** |
| 3 | appointment-example.json | JSON | **Pass** |
| 4 | appointmentresponse-example.json | JSON | **Pass** |
| 5 | auditevent-example.json | JSON | **Pass** |
| 6 | basic-example.json | JSON | **Pass** |
| 7 | binary-example.json | JSON | **Pass** |
| 8 | bodystructure-example-fetus.json | JSON | **Pass** |
| 9 | bundle-questionnaire.json | JSON | **Pass** |
| 10 | capabilitystatement-example.json | JSON | **Pass** |
| 11 | careteam-example.json | JSON | **Pass** |
| 12 | clinicalimpression-example.json | JSON | **Pass** |
| 13 | codesystem-example.json | JSON | **Pass** |
| 14 | communication-example.json | JSON | **Pass** |
| 15 | compartmentdefinition-example.json | JSON | **Pass** |
| 16 | composition-example.json | JSON | **Pass** |
| 17 | conceptmap-example.json | JSON | **Pass** |
| 18 | condition-example.json | JSON | **Pass** |
| 19 | consent-example.json | JSON | **Pass** |
| 20 | detectedissue-example.json | JSON | **Pass** |
| 21 | device-example.json | JSON | **Pass** |
| 22 | devicemetric-example.json | JSON | **Pass** |
| 23 | deviceusestatement-example.json | JSON | **Pass** |
| 24 | diagnosticreport-example.json | JSON | **Pass** |
| 25 | documentreference-example.json | JSON | **Pass** |
| 26 | encounter-example.json | JSON | **Pass** |
| 27 | endpoint-example.json | JSON | **Pass** |
| 28 | episodeofcare-example.json | JSON | **Pass** |
| 29 | familymemberhistory-example.json | JSON | **Pass** |
| 30 | flag-example.json | JSON | **Pass** |
| 31 | goal-example.json | JSON | **Pass** |
| 32 | graphdefinition-example.json | JSON | **Pass** |
| 33 | group-example.json | JSON | **Pass** |
| 34 | healthcareservice-example.json | JSON | **Pass** |
| 35 | immunization-example.json | JSON | **Pass** |
| 36 | implementationguide-example.json | JSON | **Pass** |
| 37 | linkage-example.json | JSON | **Pass** |
| 38 | list-example.json | JSON | **Pass** |
| 39 | location-example.json | JSON | **Pass** |
| 40 | medicationadministration0301.json | JSON | **Pass** |
| 41 | medicationdispense0301.json | JSON | **Pass** |
| 42 | medicationrequest0301.json | JSON | **Pass** |
| 43 | medicationstatementexample1.json | JSON | **Pass** |
| 44 | messagedefinition-example.json | JSON | **Pass** |
| 45 | messageheader-example.json | JSON | **Pass** |
| 46 | molecularsequence-example.json | JSON | **Pass** |
| 47 | namingsystem-example.json | JSON | **Pass** |
| 48 | observation-decimal.json | JSON | **Pass** |
| 49 | observation-example.json | JSON | **Pass** |
| 50 | operationdefinition-example.json | JSON | **Pass** |
| 51 | operationoutcome-example.json | JSON | **Pass** |
| 52 | organization-1.json | JSON | **Pass** |
| 53 | organization-example.json | JSON | **Pass** |
| 54 | parameters-example.json | JSON | **Pass** |
| 55 | patient-example.json | JSON | **Pass** |
| 56 | paymentnotice-example.json | JSON | **Pass** |
| 57 | person-example.json | JSON | **Pass** |
| 58 | practitioner-example.json | JSON | **Pass** |
| 59 | practitionerrole-example.json | JSON | **Pass** |
| 60 | questionnaire-example.json | JSON | **Pass** |
| 61 | questionnaireresponse-example-bluebook.json | JSON | **Pass** |
| 62 | riskassessment-example.json | JSON | **Pass** |
| 63 | schedule-example.json | JSON | **Pass** |
| 64 | searchparameter-example.json | JSON | **Pass** |
| 65 | slot-example.json | JSON | **Pass** |
| 66 | specimen-example.json | JSON | **Pass** |
| 67 | structuredefinition-example-composition.json | JSON | **Pass** |
| 68 | structuremap-example.json | JSON | **Pass** |
| 69 | subscription-example.json | JSON | **Pass** |
| 70 | substance-example.json | JSON | **Pass** |
| 71 | supplydelivery-example.json | JSON | **Pass** |
| 72 | valueset-example.json | JSON | **Pass** |
| 73 | condition-example.xml | XML | **Pass** |
| 74 | list-example-long.xml | XML | **Pass** |
| 75 | medicationdispenseexample8.xml | XML | **Pass** |
| 76 | observation-decimal.xml | XML | **Pass** |
| 77 | observation-example-20minute-apgar-score.xml | XML | **Pass** |
| 78 | observation-example.xml | XML | **Pass** |
| 79 | organization-1.xml | XML | **Pass** |
| 80 | patient-example-xds.xml | XML | **Pass** |
| 81 | patient-example.xml | XML | **Pass** |
| 82 | patient-glossy-example.xml | XML | **Pass** |
