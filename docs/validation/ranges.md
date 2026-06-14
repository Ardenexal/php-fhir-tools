---
description: Validate Quantity and date/time values against minValue/maxValue constraints.
icon: arrows-left-right-to-line
---

# Quantity & Temporal Range Validation

Validates `minValue[x]` / `maxValue[x]` constraints declared on a Structure Definition:

* `FHIRQuantityRangeValidator` — Quantity ranges, driven by the `#[FHIRQuantityRange]` attribute
* `FHIRTemporalRangeValidator` — `date` / `dateTime` / `instant` / `time` ranges, driven by the
  `#[FHIRTemporalRange]` attribute

Both validators skip `null` values, so a range constraint never fires on an absent element.

## Quantity ranges

`FHIRQuantityRangeValidator` compares a FHIR `Quantity` value against the constraint's `min` and
`max` bounds. A value below `min` or above `max` raises an ERROR:

```
The value {{ value }} is below the minimum {{ min }} {{ unit }}.
The value {{ value }} exceeds the maximum {{ max }} {{ unit }}.
```

Because units cannot be safely converted here, the comparison only proceeds when the instance and
the bound share the **same `system` + `code`**. Anything that prevents a safe comparison is
surfaced as a WARNING rather than a hard failure:

| Situation | Severity | Reason |
|---|---|---|
| Value below `min` / above `max` (units match) | ERROR | Genuine range violation |
| Instance unit differs from bound unit | WARNING | Cross-unit comparison not attempted |
| Instance missing `system` or `code` | WARNING | Cannot identify the unit |
| Configured bound missing `system` or `code` | WARNING | Malformed bound |
| Instance value carries a `comparator` | WARNING | Approximate value, not a precise measurement |

## Temporal ranges

`FHIRTemporalRangeValidator` validates `date`, `dateTime`, `instant`, and `time` values against
`min` / `max` bounds. Model properties hold primitive wrapper objects (e.g. `DatePrimitive`) which
are `\Stringable`; the validator accepts both raw strings and `\Stringable` and compares their
string form. Empty strings are skipped.

* **`time`** values are compared as plain strings.
* **Date-like** values (`date` / `dateTime` / `instant`) are parsed. Partial dates (`YYYY` or
  `YYYY-MM`) are expanded to the **start** of their period for the `min` check and the **end** for
  the `max` check (see ADR-006), so `2024` satisfies a `min` of `2024-06-01` only if the whole year
  could fall on or after the bound.

An out-of-range value raises an ERROR:

```
The value {{ value }} is before the minimum {{ min }}.
The value {{ value }} is after the maximum {{ max }}.
```

An unparseable **configured bound** is surfaced as a WARNING (`The configured {{ side }} bound
{{ bound }} is not a valid FHIR {{ type }} string.`), and an unparseable **instance value** raises
a separate diagnostic (`The value {{ value }} is not a valid FHIR {{ type }} string.`) rather than
silently passing.

See [Validation Reports & Violation Codes](reports.md) for how these severities surface in the
report.
