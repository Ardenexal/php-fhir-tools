---
category: serialization
last_reviewed: 2026-08-07
---

# Footguns: JSON Decimal Precision

## Footgun: FHIR `decimal` precision is lost at `json_decode`, before any model code runs

**Status:** active | **Created:** 2026-08-07 | **Evidence:** OBSERVED (operation-codegen M01, note N22)

The generated models carry `decimal` as a **string**, not a float — deliberately, because FHIR
requires a decimal's precision to be preserved on round-trip (`1.50` and `1.5` are different FHIR
decimals). Reading the model property and seeing a string is therefore reassuring and misleading:
the precision is already gone by the time the string exists.

`FHIRSerializationService::deserializeFromJson()` calls `json_decode()`, which turns the wire token
into a PHP **float**, and only then does the property assignment cast it to a string. Measured
(RUNTIME, 2026-08-07) on a plain `Parameters` resource with no operation or profile classes
involved:

| wire in | PHP value on the model | wire out |
|---|---|---|
| `1.50` | `'1.5'` | `1.5` |
| `0.000001` | `'1.0E-6'` | `1.0e-6` |

The second row is the worse one: the value re-serializes in scientific notation, which is a
different lexical form from what the server sent even though it is still a legal FHIR decimal.

**Why this is easy to miss.** Every obvious check passes. The property is a string as designed;
PHPStan is happy; a round-trip test that compares `1.5` to `1.5` passes; and a test that compares
decoded PHP values on both sides passes too, because both sides went through the same lossy decode.
It only shows up if you compare against the **original wire text**, which is why it surfaced during
`operation-codegen` M01 while building a structural round-trip comparator against verbatim
specification examples (`M01-prove-roundtrip-r4-r5.md`, execution note N22).

**Detection.** Compare raw JSON text, not decoded values. A decoded-value comparison is symmetric
across the defect and cannot see it.

**Current status: known and unfixed.** Pinned by
`src/Component/Serialization/tests/Unit/Operation/OperationSpecExampleRoundTripTest.php`
(search: `testDecimalPrecisionIsLostAtTheJsonBoundary`), whose docblock says to *retire* the test
rather than update it if the boundary is ever fixed. Tracked in
`.goat-flow/plans/operation-codegen/backlog.md` under "Next".

**Mitigation until then.** Do not build fixtures or conformance oracles that depend on a decimal's
lexical form surviving a round trip — a trailing-zero decimal in a test fixture will fail for this
reason and not for the reason the test is about. Fixing it properly means decoding numbers as
strings across the serializer (`JSON_BIGINT_AS_STRING` covers integers only, so this needs a real
lexer-level change) and deciding what the XML leg does to match.
