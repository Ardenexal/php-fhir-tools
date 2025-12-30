# FHIRPath 2.0 Language Specification Reference

## Introduction

This document provides a technical reference for implementing FHIRPath 2.0, extracted from the official specification. Use this as a quick reference while implementing the component.

## Grammar Overview

### Complete EBNF Grammar

```ebnf
expression
    : term (('|' | 'union') term)*
    ;

term
    : factor (('and' | 'or' | 'xor' | 'implies') factor)*
    ;

factor
    : invocation (('*' | '/' | 'div' | 'mod') invocation)*
    ;

invocation
    : primary (typeExpression | invocationExpression)*
    ;

primary
    : literal
    | externalConstant
    | '(' expression ')'
    | '{' '}'
    | function
    | '(' expression ')'
    ;

literal
    : nullLiteral
    | booleanLiteral
    | stringLiteral
    | numberLiteral
    | dateTimeLiteral
    | timeLiteral
    | quantityLiteral
    ;

function
    : identifier '(' paramList? ')'
    ;

paramList
    : expression (',' expression)*
    ;

typeExpression
    : 'is' typeSpecifier
    | 'as' typeSpecifier
    ;

typeSpecifier
    : qualifiedIdentifier
    ;

invocationExpression
    : '.' invocation
    | '[' expression ']'
    ;

externalConstant
    : '%' identifier
    ;
```

## Lexical Elements

### Keywords

```
and       or        xor       implies
is        as        contains  in
$this     $index    $total
div       mod
```

### Operators

#### Comparison Operators
- `=` : Equals
- `!=` : Not equals
- `~` : Equivalent
- `!~` : Not equivalent
- `>` : Greater than
- `<` : Less than
- `>=` : Greater than or equal
- `<=` : Less than or equal

#### Logical Operators
- `and` : Logical AND
- `or` : Logical OR
- `xor` : Exclusive OR
- `implies` : Logical implication

#### Mathematical Operators
- `+` : Addition / String concatenation
- `-` : Subtraction / Negation
- `*` : Multiplication
- `/` : Division
- `div` : Integer division
- `mod` : Modulus

#### Collection Operators
- `|` : Union
- `&` : Intersection
- `in` : Membership test
- `contains` : Contains test

### Literals

#### Boolean Literals
```
true
false
```

#### String Literals
```
'hello'
'it\'s'
'line\nbreak'
'unicode\u0041'
```

Escape sequences:
- `\'` : Single quote
- `\"` : Double quote (when using double quotes)
- `\\` : Backslash
- `\t` : Tab
- `\n` : Newline
- `\r` : Carriage return
- `\f` : Form feed
- `\uXXXX` : Unicode character

#### Number Literals
```
42          // Integer
3.14        // Decimal
2.5e10      // Scientific notation
```

#### DateTime Literals
```
@2023-12-25                    // Date
@2023-12-25T12:30:00           // DateTime
@2023-12-25T12:30:00.123       // DateTime with milliseconds
@2023-12-25T12:30:00+01:00     // DateTime with timezone
@T12:30:00                     // Time only
```

#### Quantity Literals
```
5 'mg'           // Quantity with UCUM unit
10.5 'cm'        // Decimal quantity
100 '[degF]'     // Fahrenheit degrees
```

### Identifiers

Valid identifier patterns:
```
name              // Simple identifier
_name             // Can start with underscore
name123           // Can contain digits
```

Reserved identifiers:
```
$this             // Current node
$index            // Current index in collection
$total            // Total items in collection
```

## Operator Precedence

From highest to lowest precedence:

| Precedence | Operators | Associativity | Description |
|-----------|-----------|---------------|-------------|
| 1 | `.` | Left | Member/function invocation |
| 2 | `[]` | Left | Indexer |
| 3 | unary `-`, `+` | Right | Unary negation/positive |
| 4 | `*`, `/`, `div`, `mod` | Left | Multiplicative |
| 5 | `+`, `-`, `&` | Left | Additive, concatenation |
| 6 | `\|` | Left | Union |
| 7 | `>`, `<`, `>=`, `<=` | Left | Inequality |
| 8 | `is`, `as` | Left | Type operations |
| 9 | `=`, `~`, `!=`, `!~` | Left | Equality |
| 10 | `in`, `contains` | Left | Membership |
| 11 | `and` | Left | Logical AND |
| 12 | `or`, `xor` | Left | Logical OR/XOR |
| 13 | `implies` | Right | Logical implication |

## Type System

### System Types

FHIRPath defines these fundamental types:

```
System.Any                    // Base type for all values
├── System.Boolean
├── System.String
├── System.Integer
├── System.Long
├── System.Decimal
├── System.Date
├── System.DateTime
├── System.Time
├── System.Quantity
└── (FHIR types inherit from here)
```

### Type Conversion Rules

#### Implicit Conversions

```
Integer → Long → Decimal
Integer → Quantity (with dimensionless unit)
String → all types (via parsing)
```

#### Explicit Conversions

All types can be explicitly converted using:
- `value.toString()`
- `value.toInteger()`
- `value.toDecimal()`
- `value.toDate()`
- `value.toDateTime()`
- `value.toTime()`
- `value.toBoolean()`

### Type Checking

```fhirpath
value is Integer              // Returns Boolean
value as Integer              // Returns value cast to Integer or empty
value ofType(Patient)         // Filters collection to Patient resources
```

## Collections

### Collection Semantics

**Key principle**: Everything in FHIRPath is a collection.

- Empty collection: `{}`
- Single item collection: `{value}`
- Multiple items: `{value1, value2, value3}`

### Collection Operations

#### Creation
```fhirpath
{}                           // Empty collection
{1, 2, 3}                   // Literal collection
```

#### Access
```fhirpath
collection[0]                // First item (0-indexed)
collection.first()           // First item (function)
collection.last()            // Last item
collection.tail()            // All but first
```

#### Filtering
```fhirpath
collection.where(condition)  // Filter by condition
collection.ofType(Type)      // Filter by type
```

#### Transformation
```fhirpath
collection.select(expr)      // Transform each item
collection.repeat(expr)      // Recursive transform
```

#### Aggregation
```fhirpath
collection.count()           // Count items
collection.sum()             // Sum numeric values
collection.min()             // Minimum value
collection.max()             // Maximum value
collection.avg()             // Average value
```

#### Combination
```fhirpath
col1 | col2                  // Union
col1.union(col2)             // Union (function)
col1.combine(col2)           // Concatenate
col1.intersect(col2)         // Intersection
col1.exclude(col2)           // Exclude items
```

## Functions Reference

### Existence Functions

#### empty()
Returns true if collection is empty.
```fhirpath
name.empty()                 // true if name is empty
```

#### exists([criteria])
Returns true if collection has any items (optionally matching criteria).
```fhirpath
name.exists()                // true if name has any value
telecom.exists(system = 'phone')  // true if any phone exists
```

#### all(criteria)
Returns true if all items match criteria.
```fhirpath
telecom.all(system = 'phone')    // true if all are phones
```

#### allTrue() / anyTrue() / allFalse() / anyFalse()
Boolean aggregations.
```fhirpath
conditions.all(active = true)    // Check all conditions active
```

### Filtering and Projection

#### where(criteria)
Filter collection by criteria.
```fhirpath
name.where(use = 'official')     // Official names only
telecom.where(system = 'phone')  // Phone numbers only
```

#### select(projection)
Transform each item.
```fhirpath
name.select(given)               // Extract given names
telecom.select(value)            // Extract values
```

#### repeat(projection)
Recursive projection.
```fhirpath
descendants.repeat(children)     // All descendants recursively
```

#### ofType(type)
Filter by type.
```fhirpath
extension.ofType(Extension)      // Extensions only
```

#### first() / last()
Get first or last item.
```fhirpath
name.first()                     // First name
telecom.last()                   // Last telecom
```

#### tail() / skip(num) / take(num)
Collection slicing.
```fhirpath
collection.tail()                // All but first
collection.skip(2)               // Skip first 2
collection.take(3)               // Take first 3
```

### String Functions

#### substring(start, [length])
Extract substring.
```fhirpath
name.substring(0, 3)             // First 3 characters
name.substring(2)                // From position 2 to end
```

#### startsWith(prefix) / endsWith(suffix)
Check string start/end.
```fhirpath
name.startsWith('Dr.')           // Starts with Dr.
name.endsWith('Jr.')             // Ends with Jr.
```

#### contains(substring)
Check if contains substring.
```fhirpath
name.contains('John')            // Contains John
```

#### indexOf(substring)
Find position of substring.
```fhirpath
name.indexOf('John')             // Position of John (0-indexed)
```

#### upper() / lower()
Case conversion.
```fhirpath
name.upper()                     // UPPERCASE
name.lower()                     // lowercase
```

#### replace(pattern, replacement)
String replacement.
```fhirpath
name.replace('Jr.', 'Junior')    // Replace Jr. with Junior
```

#### matches(regex)
Regex matching.
```fhirpath
name.matches('[A-Z][a-z]+')      // Match pattern
```

#### replaceMatches(regex, replacement)
Regex replacement.
```fhirpath
name.replaceMatches('\\d+', 'X') // Replace digits with X
```

#### length()
String length.
```fhirpath
name.length()                    // Length of name
```

#### trim()
Remove whitespace.
```fhirpath
name.trim()                      // Remove leading/trailing whitespace
```

#### split(separator)
Split string.
```fhirpath
name.split(' ')                  // Split by space
```

### Math Functions

#### abs()
Absolute value.
```fhirpath
(-5).abs()                       // 5
```

#### ceiling() / floor()
Round up/down.
```fhirpath
(3.7).ceiling()                  // 4
(3.7).floor()                    // 3
```

#### truncate()
Remove decimal part.
```fhirpath
(3.7).truncate()                 // 3
```

#### round([precision])
Round to precision.
```fhirpath
(3.14159).round(2)               // 3.14
```

#### exp() / ln() / log(base)
Exponential and logarithms.
```fhirpath
(2).exp()                        // e^2
(10).ln()                        // Natural log of 10
(100).log(10)                    // Log base 10 of 100
```

#### power(exponent)
Exponentiation.
```fhirpath
(2).power(8)                     // 2^8 = 256
```

#### sqrt()
Square root.
```fhirpath
(16).sqrt()                      // 4
```

### Date/Time Functions

#### now()
Current date and time.
```fhirpath
now()                            // Current datetime
```

#### today()
Current date (no time).
```fhirpath
today()                          // Current date
```

#### timeOfDay()
Current time (no date).
```fhirpath
timeOfDay()                      // Current time
```

### Type Functions

#### convertsToInteger() / convertsToDecimal() / etc.
Check if value can be converted.
```fhirpath
value.convertsToInteger()        // Can convert to integer?
```

#### toInteger() / toDecimal() / toString() / etc.
Convert to specific type.
```fhirpath
'42'.toInteger()                 // 42
42.toString()                    // '42'
```

#### is(type)
Type checking.
```fhirpath
value is Integer                 // Is value an Integer?
```

#### as(type)
Type casting.
```fhirpath
value as Integer                 // Cast to Integer (or empty)
```

## Operator Semantics

### Equality vs Equivalence

#### Equality (=, !=)
Strict equality with type consideration.
- Different types: empty result
- `null` propagation: `null = x` → empty

```fhirpath
'42' = 42                        // {} (empty - different types)
42 = 42                          // true
{} = 42                          // {} (empty)
```

#### Equivalence (~, !~)
Type-aware equivalence.
- Type conversion allowed
- `null` handling: `null ~ null` → true

```fhirpath
'42' ~ 42                        // true (converts to same type)
42 ~ 42                          // true
{} ~ {}                          // true (both empty)
```

### Boolean Logic

#### Three-Valued Logic

FHIRPath uses three-valued logic (true, false, empty):

| A | B | A and B | A or B | A xor B | A implies B |
|---|---|---------|--------|---------|-------------|
| T | T | T | T | F | T |
| T | F | F | T | T | F |
| T | {} | {} | T | {} | {} |
| F | T | F | T | T | T |
| F | F | F | F | F | T |
| F | {} | F | {} | {} | T |
| {} | T | {} | T | {} | {} |
| {} | F | F | {} | {} | {} |
| {} | {} | {} | {} | {} | {} |

### Collection Operations

#### Union (|)
Combine collections, removing duplicates.
```fhirpath
{1, 2} | {2, 3}                  // {1, 2, 3}
```

#### Intersection (&)
Common elements.
```fhirpath
{1, 2, 3} & {2, 3, 4}           // {2, 3}
```

### Path Navigation

#### Simple Navigation
```fhirpath
Patient.name                     // All names
Patient.name.given               // All given names
```

#### Polymorphic Navigation
```fhirpath
Observation.value                // All value[x] elements
Observation.valueQuantity        // Only valueQuantity
Observation.value.ofType(Quantity)  // Filter to Quantity
```

#### Array Indexing
```fhirpath
name[0]                          // First name (0-indexed)
name.first()                     // Same as above (preferred)
```

## Error Handling

### Error Types

1. **Syntax Errors**: Invalid expression syntax
2. **Type Errors**: Type mismatches
3. **Evaluation Errors**: Runtime errors
4. **Empty Propagation**: Operations on empty collections

### Empty Propagation Rules

Most operations on empty collections return empty:

```fhirpath
{}.name                          // {} (empty)
{}.exists()                      // false
{}.empty()                       // true
{}.count()                       // 0
```

## Special Contexts

### Iteration Context

Inside `where()`, `select()`, etc.:

```fhirpath
collection.where($this > 5)      // $this is current item
collection.select($index)        // $index is position (0-based)
collection.where($index < $total)// $total is collection size
```

### External Constants

Access external values with `%`:

```fhirpath
%ucum                            // UCUM definitions
%vs-myValueSet                   // Value set reference
```

## Performance Considerations

### Lazy Evaluation

FHIRPath should use lazy evaluation:
- Don't evaluate more than needed
- Short-circuit boolean operators
- Stream large collections

### Index Optimization

Some expressions can be optimized:
```fhirpath
// Instead of:
Patient.name.where(use = 'official').first()

// Optimize to:
Patient.name.where(use = 'official')[0]
```

### Path Caching

Cache frequently used paths:
```fhirpath
// Cache resolution of:
Patient.name.given.first()
```

## Common Patterns

### Checking for Value
```fhirpath
element.exists()                 // Has any value?
element.empty()                  // Has no value?
```

### Safe Navigation
```fhirpath
element.exists() and element.value > 0
element.value > 0                // Equivalent (empty safe)
```

### Conditional Logic
```fhirpath
iif(condition, trueResult, falseResult)
```

### Type Checking
```fhirpath
value is Integer                 // Check type
value as Integer                 // Cast (returns empty if not)
value.ofType(Integer)            // Filter by type
```

### Finding First Match
```fhirpath
collection.where(criteria).first()
collection.where(criteria)[0]    // Same
```

### Checking All Match
```fhirpath
collection.all(criteria)         // All match
collection.where(criteria).count() = collection.count()  // Equivalent
```

## Testing Expressions

### Test Cases Structure

```json
{
  "subject": "resource.json",
  "tests": [
    {
      "expression": "Patient.name.given",
      "expected": ["John", "Jane"]
    },
    {
      "expression": "Patient.name.where(use='official').exists()",
      "expected": [true]
    }
  ]
}
```

### Edge Cases to Test

1. Empty collections
2. Single vs multiple items
3. Type conversions
4. Null handling
5. Polymorphic elements
6. Deep nesting
7. Large collections
8. Special characters in strings
9. Boundary values for numbers
10. Invalid paths

## Reference Resources

- **FHIRPath Specification**: http://hl7.org/fhirpath/N1/
- **FHIRPath Grammar**: http://hl7.org/fhirpath/N1/grammar.html
- **FHIRPath Tests**: http://hl7.org/fhirpath/tests.html
- **FHIR Specification**: http://hl7.org/fhir/

## Implementation Checklist

- [ ] Lexer recognizes all tokens
- [ ] Parser handles all grammar rules
- [ ] Operator precedence correct
- [ ] All functions implemented
- [ ] All operators implemented
- [ ] Type system complete
- [ ] Collection semantics correct
- [ ] Empty propagation correct
- [ ] Three-valued logic correct
- [ ] Error handling comprehensive
- [ ] Performance optimized
- [ ] Tests pass specification suite
