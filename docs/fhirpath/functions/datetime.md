---
description: Date and time functions.
icon: clock
---

# Date & Time

Functions that provide the current date/time or convert a quantity to a time unit.

| Function | Description | Example |
|----------|-------------|---------|
| `now()` | Returns the current date and time as a DateTime. | `now()` |
| `today()` | Returns the current date as a Date. | `Patient.birthDate < today()` |
| `timeOfDay()` | Returns the current time of day as a Time. | `timeOfDay()` |
| `toMilliseconds()` | Converts a time-valued quantity to milliseconds. | `(1 's').toMilliseconds()` → `1000` |
| `toSeconds()` | Converts a time-valued quantity to seconds. | `(2 'min').toSeconds()` → `120` |

{% hint style="info" %}
Conversion functions for parsing strings into dates and times — `toDate()`,
`toDateTime()`, `toTime()` and their `convertsTo*()` companions — live under
[Type Conversion & Checking](types.md).
{% endhint %}
