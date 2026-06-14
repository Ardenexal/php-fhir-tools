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
