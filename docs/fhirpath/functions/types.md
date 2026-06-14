---
description: Type conversion and type checking functions.
icon: arrow-right-arrow-left
---

# Type Conversion & Checking

## Conversion

Each `toX()` function converts a single-item input to the target type (returning
empty when conversion is not possible). Each `convertsToX()` companion returns a
Boolean reporting whether the conversion would succeed, without performing it.

| Function | Description | Example |
|----------|-------------|---------|
| `toBoolean()` / `convertsToBoolean()` | Convert to / test convertibility to Boolean. | `'true'.toBoolean()` → `true` |
| `toInteger()` / `convertsToInteger()` | Convert to / test convertibility to Integer. | `'42'.toInteger()` → `42` |
| `toDecimal()` / `convertsToDecimal()` | Convert to / test convertibility to Decimal. | `'3.14'.toDecimal()` |
| `toDate()` / `convertsToDate()` | Convert to / test convertibility to Date. | `'2024-01-15'.toDate()` |
| `toDateTime()` / `convertsToDateTime()` | Convert to / test convertibility to DateTime. | `'2024-01-15T10:00:00'.toDateTime()` |
| `toTime()` / `convertsToTime()` | Convert to / test convertibility to Time. | `'10:30:00'.toTime()` |
| `toQuantity([unit])` / `convertsToQuantity([unit])` | Convert to / test convertibility to Quantity. | `'5 mg'.toQuantity()` |
| `toString()` / `convertsToString()` | Convert to / test convertibility to String. | `(42).toString()` → `'42'` |

## Checking

| Function | Description | Example |
|----------|-------------|---------|
| `ofType(type)` | Filters the collection to items of the given type. | `Bundle.entry.resource.ofType(Patient)` |
| `type()` | Returns type information (namespace + name) for each item. | `Patient.active.type()` |

{% hint style="info" %}
Type specifiers accept both the `System.` namespace (e.g. `System.Boolean`) and the
`FHIR.` namespace (e.g. `FHIR.Patient`). For runtime type testing with the `is` and
`as` operators, see [Expressions & Operators](../expressions.md).
{% endhint %}
