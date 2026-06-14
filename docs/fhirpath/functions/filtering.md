---
description: Filtering, subsetting, and combining functions.
icon: filter
---

# Filtering & Subsetting

Functions that select a subset of a collection or combine collections together.

## Filtering & subsetting

| Function | Description | Example |
|----------|-------------|---------|
| `where(criteria)` | Returns only items for which the criteria expression evaluates to true. | `Patient.name.where(use = 'official')` |
| `select(projection)` | Projects each item through an expression and flattens the results. | `Patient.name.select(given)` |
| `first()` | Returns the first item (empty if the input is empty). | `Patient.name.first()` |
| `last()` | Returns the last item. | `Patient.name.last()` |
| `tail()` | Returns all items except the first. | `Patient.name.tail()` |
| `skip(num)` | Returns all items after the first `num` items. | `Patient.name.skip(1)` |
| `take(num)` | Returns the first `num` items. | `Patient.name.take(2)` |
| `single()` | Returns the single item; errors if the collection has more than one item. | `Patient.name.where(use = 'official').single()` |
| `distinct()` | Returns the collection with duplicate items removed. | `Patient.name.given.distinct()` |

## Combining

| Function | Description | Example |
|----------|-------------|---------|
| `union(other)` | Merges two collections, removing duplicates (function form of `\|`). | `name.given.union(name.family)` |
| `intersect(other)` | Returns items present in both collections. | `a.intersect(b)` |
| `exclude(other)` | Returns input items that are not present in `other`. | `a.exclude(b)` |
| `combine(other)` | Merges two collections keeping duplicates. | `name.given.combine(name.family)` |

{% hint style="info" %}
`sort()` and the conditional `iif()` are documented under
[Tree Navigation & Utility](navigation.md).
{% endhint %}
