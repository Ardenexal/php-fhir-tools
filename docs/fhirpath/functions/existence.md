---
description: Existence and collection functions.
icon: list
---

# Existence & Collection

Functions that test for the presence, count, and ordering relationships of items
in a collection. Most return a single Boolean.

| Function | Description | Example |
|----------|-------------|---------|
| `empty()` | True when the input collection has no items. | `Patient.name.empty()` |
| `exists([criteria])` | True when the collection is non-empty; with an optional filter expression, true when any item matches. | `Patient.telecom.exists(system = 'phone')` |
| `all(criteria)` | True when every item matches the given expression (true for an empty input). | `Patient.name.all(use = 'official')` |
| `count()` | Returns the number of items as an Integer. | `Patient.name.count()` |
| `allTrue()` | True when every Boolean item is true. | `Patient.active.allTrue()` |
| `anyTrue()` | True when at least one Boolean item is true. | `(true \| false).anyTrue()` |
| `allFalse()` | True when every Boolean item is false. | `(false \| false).allFalse()` |
| `anyFalse()` | True when at least one Boolean item is false. | `(true \| false).anyFalse()` |
| `subsetOf(other)` | True when every item in the input also exists in `other`. | `name.subsetOf(%context.name)` |
| `supersetOf(other)` | True when every item in `other` also exists in the input. | `name.supersetOf(%context.name)` |
| `isDistinct()` | True when the collection contains no duplicate items. | `Patient.name.given.isDistinct()` |
| `not()` | Logical negation of a single Boolean input (function form of `!`). | `Patient.active.not()` |

{% hint style="info" %}
The tree-traversal helper `repeat()` is documented under
[Tree Navigation & Utility](navigation.md).
{% endhint %}
