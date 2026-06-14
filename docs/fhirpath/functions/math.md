---
description: Mathematical functions.
icon: calculator
---

# Math

Math functions operate on numeric values. Single-value functions (`abs()`,
`ceiling()`, etc.) expect a single-item numeric input; aggregate functions
(`sum()`, `min()`, `max()`, `avg()`) operate over the whole collection.

| Function | Description | Example |
|----------|-------------|---------|
| `sum()` | Sum of all numeric items in the collection. | `(1 \| 2 \| 3).sum()` → `6` |
| `abs()` | Absolute value. | `(-5).abs()` → `5` |
| `ceiling()` | Smallest integer greater than or equal to the input. | `(1.1).ceiling()` → `2` |
| `floor()` | Largest integer less than or equal to the input. | `(1.9).floor()` → `1` |
| `truncate()` | Integer part, dropping any fraction. | `(1.9).truncate()` → `1` |
| `round([precision])` | Rounds to the given number of decimal places (default 0). | `(3.14159).round(2)` → `3.14` |
| `exp()` | e raised to the power of the input. | `(1).exp()` → `2.718...` |
| `ln()` | Natural logarithm. | `(2.718281828).ln()` → `1.0` |
| `log(base)` | Logarithm to the given base. | `(100).log(10)` → `2` |
| `power(exponent)` | Raises the input to `exponent`. | `(2).power(3)` → `8` |
| `sqrt()` | Square root. | `(9).sqrt()` → `3` |
| `min()` | Smallest item in the collection. | `(3 \| 1 \| 2).min()` → `1` |
| `max()` | Largest item in the collection. | `(3 \| 1 \| 2).max()` → `3` |
| `avg()` | Arithmetic mean of the collection. | `(2 \| 4 \| 6).avg()` → `4` |

## Precision & boundaries

These three functions report or derive the precision of a value. They accept a
decimal, integer, date, dateTime, or time, using FHIRPath positional precision
numbers (e.g. `YYYY`=4, `YYYY-MM`=6, `YYYY-MM-DD`=8 for dates; digits after the
decimal point for numbers). `lowBoundary()` and `highBoundary()` take an optional
output-precision argument.

| Function | Description | Example |
|----------|-------------|---------|
| `precision()` | Number of significant positions in the input. Trailing zeros on a decimal are preserved. | `(1.58700).precision()` → `5` |
| `lowBoundary([precision])` | Lowest value in the natural range the input represents. For numbers, `value - 0.5×10^-precision`; for dates/times, fills unspecified components with their minimum. | `(1.587).lowBoundary()` → `1.5865...` |
| `highBoundary([precision])` | Highest value in the natural range the input represents. For numbers, `value + 0.5×10^-precision`; for dates/times, fills unspecified components with their maximum. | `(1.587).highBoundary()` → `1.5875...` |
