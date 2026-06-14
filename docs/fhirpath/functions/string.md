---
description: String manipulation functions.
icon: quote-right
---

# String

String functions operate on a single-item String input unless noted otherwise.

| Function | Description | Example |
|----------|-------------|---------|
| `substring(start [, length])` | Returns the substring from `start` (0-based) for `length` characters. | `'abcdef'.substring(0, 3)` → `'abc'` |
| `length()` | Returns the character count as an Integer. | `'abc'.length()` → `3` |
| `startsWith(prefix)` | True when the string begins with `prefix`. | `'abc'.startsWith('ab')` |
| `endsWith(suffix)` | True when the string ends with `suffix`. | `'abc'.endsWith('bc')` |
| `contains(substring)` | True when the string contains `substring`. | `'abc'.contains('b')` |
| `indexOf(substring)` | 0-based index of the first match, or `-1`. | `'abc'.indexOf('b')` → `1` |
| `upper()` | Converts to upper case. | `'abc'.upper()` → `'ABC'` |
| `lower()` | Converts to lower case. | `'ABC'.lower()` → `'abc'` |
| `replace(pattern, substitution)` | Replaces all plain-string occurrences of `pattern`. | `'abc'.replace('b', 'x')` → `'axc'` |
| `replaceMatches(regex, substitution)` | Regex-based replace. | `'abc123'.replaceMatches('[0-9]', 'x')` |
| `matches(regex)` | True when the regex matches anywhere in the string. | `'abc'.matches('[a-z]+')` |
| `matchesFull(regex)` | True when the regex matches the entire string. | `'abc'.matchesFull('[a-z]+')` |
| `trim()` | Removes leading and trailing whitespace. | `'  abc  '.trim()` → `'abc'` |
| `split(separator)` | Splits the string into a collection. | `'a,b,c'.split(',')` |
| `toChars()` | Splits the string into a collection of single characters. | `'abc'.toChars()` |
| `join([separator])` | Joins a collection of strings into one string. | `('a' \| 'b').join(',')` → `'a,b'` |
| `encode(format)` | Encodes a string (`hex`, `base64`, `urlbase64`). | `'abc'.encode('base64')` |
| `decode(format)` | Decodes a string using the given format. | `'YWJj'.decode('base64')` → `'abc'` |
| `escape(target)` | Escapes a string for `html` or `json`. | `'<a>'.escape('html')` |
| `unescape(target)` | Reverses `escape()` for `html` or `json`. | `'&lt;a&gt;'.unescape('html')` |
