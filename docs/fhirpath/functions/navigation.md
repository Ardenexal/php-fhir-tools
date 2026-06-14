---
description: Tree navigation and utility functions.
icon: sitemap
---

# Tree Navigation & Utility

## Tree navigation

| Function | Description | Example |
|----------|-------------|---------|
| `children()` | Returns all direct child nodes of each input item. | `Patient.children()` |
| `descendants()` | Returns all descendant nodes recursively (shorthand for `repeat(children())`). | `Patient.descendants()` |
| `repeat(projection)` | Applies the projection repeatedly, unioning results until no new items appear (transitive closure). Cycle-safe. | `Questionnaire.repeat(item)` |

## Utility

| Function | Description | Example |
|----------|-------------|---------|
| `trace(name [, projection])` | Logs diagnostic output and returns the input unchanged. | `Patient.name.trace('names').given` |
| `aggregate(aggregator [, init])` | Reduces a collection to a single value; exposes `$this`, `$index`, and `$total`. | `value.aggregate($this + $total, 0)` |
| `iif(condition, ifTrue [, ifFalse])` | Conditional expression; returns `ifTrue` when the condition is true, otherwise `ifFalse`. | `iif(active, 'yes', 'no')` |
| `sort([key, ...])` | Sorts by natural order, or by one or more projection keys (use unary `-` for descending). | `name.given.sort()` / `item.sort(-priority)` |

{% hint style="info" %}
`trace()` writes to a PSR-3 logger when one is configured via
`FHIRPathEvaluator::setLogger()`, otherwise it falls back to PHP's `error_log()`.
{% endhint %}
