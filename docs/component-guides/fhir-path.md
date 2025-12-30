# FHIRPath 2.0 Component Requirements

## Overview

This document outlines the requirements for implementing a FHIRPath 2.0 component in PHP FHIRTools. The component will provide a standalone library for evaluating FHIRPath expressions against FHIR resources, following the [FHIRPath 2.0 specification](http://hl7.org/fhirpath/N1/).

## Component Information

- **Name**: `ardenexal/fhir-path`
- **Type**: `library`
- **Purpose**: Standalone FHIRPath 2.0 expression evaluation and parsing
- **Location**: `src/Component/FHIRPath/`

## FHIRPath 2.0 Specification Overview

FHIRPath is a path-based navigation and extraction language designed for FHIR resources. Key features include:

### Core Language Features
1. **Path Navigation**: Navigate through FHIR resource structures
2. **Collections**: All values are collections (empty, single, or multiple items)
3. **Functions**: Built-in functions for filtering, transformation, and aggregation
4. **Operators**: Comparison, logical, mathematical, and string operators
5. **Type System**: Strong typing aligned with FHIR data types
6. **Polymorphism**: Support for polymorphic FHIR elements

### FHIRPath 2.0 Enhancements
- Improved type handling
- Enhanced function library
- Better error reporting
- Performance optimizations
- Extended operator support

## Architecture Alignment

The FHIRPath component follows the established multi-component architecture:

```
src/Component/FHIRPath/
├── src/
│   ├── Parser/
│   │   ├── FHIRPathParser.php
│   │   ├── FHIRPathLexer.php
│   │   ├── Token.php
│   │   └── Exception/
│   │       ├── ParseException.php
│   │       └── SyntaxException.php
│   ├── Evaluator/
│   │   ├── FHIRPathEvaluator.php
│   │   ├── EvaluationContext.php
│   │   ├── Collection.php
│   │   └── Exception/
│   │       ├── EvaluationException.php
│   │       └── TypeException.php
│   ├── Expression/
│   │   ├── ExpressionNode.php
│   │   ├── LiteralNode.php
│   │   ├── FunctionNode.php
│   │   ├── OperatorNode.php
│   │   ├── PathNode.php
│   │   └── InvocationNode.php
│   ├── Function/
│   │   ├── FunctionRegistry.php
│   │   ├── FunctionInterface.php
│   │   ├── Core/                     # Core functions
│   │   │   ├── EmptyFunction.php
│   │   │   ├── ExistsFunction.php
│   │   │   ├── AllFunction.php
│   │   │   └── CountFunction.php
│   │   ├── String/                   # String functions
│   │   │   ├── SubstringFunction.php
│   │   │   ├── StartsWithFunction.php
│   │   │   └── EndsWithFunction.php
│   │   ├── Math/                     # Mathematical functions
│   │   │   ├── AbsFunction.php
│   │   │   ├── CeilingFunction.php
│   │   │   └── FloorFunction.php
│   │   ├── Date/                     # Date/time functions
│   │   │   ├── NowFunction.php
│   │   │   └── TodayFunction.php
│   │   └── Aggregate/                # Aggregation functions
│   │       ├── SumFunction.php
│   │       ├── MinFunction.php
│   │       └── MaxFunction.php
│   ├── Operator/
│   │   ├── OperatorRegistry.php
│   │   ├── OperatorInterface.php
│   │   ├── Comparison/
│   │   │   ├── EqualsOperator.php
│   │   │   ├── NotEqualsOperator.php
│   │   │   └── GreaterThanOperator.php
│   │   ├── Logical/
│   │   │   ├── AndOperator.php
│   │   │   ├── OrOperator.php
│   │   │   └── ImpliesOperator.php
│   │   └── Mathematical/
│   │       ├── AddOperator.php
│   │       ├── SubtractOperator.php
│   │       └── MultiplyOperator.php
│   ├── Type/
│   │   ├── FHIRPathTypeSystem.php
│   │   ├── TypeConverter.php
│   │   ├── TypeValidator.php
│   │   └── TypeInfo.php
│   ├── Service/
│   │   ├── FHIRPathService.php
│   │   └── FHIRPathCompiler.php
│   └── Exception/
│       ├── FHIRPathException.php
│       ├── InvalidExpressionException.php
│       └── UnsupportedOperationException.php
├── tests/
│   ├── Unit/
│   │   ├── Parser/
│   │   ├── Evaluator/
│   │   ├── Expression/
│   │   ├── Function/
│   │   └── Operator/
│   ├── Integration/
│   │   ├── ExpressionEvaluationTest.php
│   │   └── FHIRResourceNavigationTest.php
│   └── Fixtures/
│       ├── expressions.json
│       └── test-resources.json
├── composer.json
└── README.md
```

## Core Requirements

### 1. Lexical Analysis (Lexer)

**Purpose**: Tokenize FHIRPath expressions into a stream of tokens.

**Requirements**:
- Recognize keywords: `and`, `or`, `xor`, `implies`, `as`, `is`, `where`, `select`, etc.
- Identify operators: `=`, `!=`, `~`, `!~`, `>`, `<`, `>=`, `<=`, `+`, `-`, `*`, `/`, `div`, `mod`, `|`, `&`
- Parse literals: strings, numbers, booleans, dates, times, quantities
- Handle delimiters: `.`, `(`, `)`, `[`, `]`, `,`, `{`, `}`
- Recognize identifiers and function names
- Support escape sequences in strings
- Line and column number tracking for error reporting

**Key Classes**:
- `FHIRPathLexer`: Main lexer implementation
- `Token`: Represents individual tokens with type, value, position
- `TokenType` (enum): All token types

### 2. Syntax Analysis (Parser)

**Purpose**: Parse token stream into an Abstract Syntax Tree (AST).

**Requirements**:
- Recursive descent parser implementation
- Operator precedence handling (unary, multiplication, addition, comparison, logical)
- Expression tree construction
- Support for:
  - Path expressions (`Patient.name.given`)
  - Function invocations (`where(active = true)`)
  - Operators (all FHIRPath operators)
  - Literals (strings, numbers, booleans, quantities)
  - Collections and indexing
  - Type expressions (`as`, `is`, `ofType`)
- Comprehensive error messages with position information
- AST optimization opportunities

**Key Classes**:
- `FHIRPathParser`: Main parser implementation
- `ExpressionNode`: Base AST node
- Specialized node types for each expression kind
- `ParseException`, `SyntaxException`: Error handling

### 3. Expression Evaluation

**Purpose**: Evaluate parsed AST against FHIR resources.

**Requirements**:
- Context-aware evaluation
- Collection-based value handling
- Type coercion and conversion
- Lazy evaluation where possible
- Short-circuit evaluation for logical operators
- Support for polymorphic elements (e.g., `value[x]`)
- Resource path navigation
- Reference resolution capability
- Extension handling
- Error propagation with context

**Key Classes**:
- `FHIRPathEvaluator`: Main evaluation engine
- `EvaluationContext`: Maintains evaluation state
- `Collection`: Represents FHIRPath collections
- `EvaluationException`, `TypeException`: Error handling

### 4. Function Library

**Purpose**: Implement all FHIRPath 2.0 standard functions.

**Required Function Categories**:

#### Existence Functions (4 functions)
- `empty()`: Returns true if collection is empty
- `exists([criteria])`: Returns true if any items match criteria
- `all(criteria)`: Returns true if all items match criteria
- `allTrue()`, `anyTrue()`, `allFalse()`, `anyFalse()`: Boolean aggregations

#### Filtering and Projection (6 functions)
- `where(criteria)`: Filter collection by criteria
- `select(projection)`: Transform each item
- `repeat(projection)`: Recursive projection
- `ofType(type)`: Filter by type
- `first()`, `last()`: Get first/last item
- `tail()`, `skip(num)`, `take(num)`: Collection slicing

#### Subsetting Functions (5 functions)
- `single()`: Assert single item
- `distinct()`: Remove duplicates
- `isDistinct()`: Check if all distinct
- `intersect(other)`, `exclude(other)`: Set operations

#### Combining Functions (3 functions)
- `union(other)`: Combine collections
- `combine(other)`: Concatenate collections
- `|` operator: Union/pipe operator

#### Conversion Functions (8 functions)
- `iif(criterion, true-result, false-result)`: Conditional
- `toInteger()`, `toDecimal()`, `toString()`: Type conversion
- `toBoolean()`, `toDate()`, `toDateTime()`, `toTime()`: More conversions
- `convertsToInteger()`, `convertsToDecimal()`, etc.: Conversion checks

#### String Functions (12 functions)
- `indexOf(substring)`, `substring(start, length)`: String manipulation
- `startsWith(prefix)`, `endsWith(suffix)`: String checking
- `contains(substring)`: Substring check
- `upper()`, `lower()`: Case conversion
- `replace(pattern, replacement)`: String replacement
- `matches(regex)`, `replaceMatches(regex, replacement)`: Regex support
- `length()`: String length
- `trim()`: Remove whitespace
- `split(separator)`: String splitting

#### Math Functions (13 functions)
- `abs()`, `ceiling()`, `floor()`: Basic math
- `truncate()`, `round([precision])`: Rounding
- `exp()`, `ln()`, `log(base)`: Exponentials and logarithms
- `power(exponent)`: Exponentiation
- `sqrt()`: Square root
- `sum()`, `min()`, `max()`, `avg()`: Aggregations

#### Date/Time Functions (6 functions)
- `now()`, `timeOfDay()`, `today()`: Current date/time
- `toMilliseconds()`: Convert to ms since epoch
- `toSeconds()`: Convert to seconds
- Custom date/time arithmetic

#### Aggregate Functions (4 functions)
- `count()`: Count items
- `sum()`: Numeric sum
- `min()`, `max()`: Min/max values
- `avg()`: Average

#### Type and Reflection (4 functions)
- `is(type)`: Type checking
- `as(type)`: Type casting
- `ofType(type)`: Type filtering
- `conformsTo(structure)`: Structure validation

**Key Classes**:
- `FunctionRegistry`: Register and lookup functions
- `FunctionInterface`: Contract for all functions
- Individual function implementations organized by category

### 5. Operator Support

**Purpose**: Implement all FHIRPath operators with correct precedence.

**Required Operators**:

#### Comparison Operators
- `=`, `!=`: Equality (with null handling)
- `~`, `!~`: Equivalence (type-aware equality)
- `>`, `<`, `>=`, `<=`: Ordering comparisons

#### Mathematical Operators
- `+`, `-`: Addition, subtraction (and string concatenation)
- `*`, `/`: Multiplication, division
- `div`, `mod`: Integer division, modulo

#### Logical Operators
- `and`, `or`, `xor`: Boolean logic
- `implies`: Logical implication

#### Collection Operators
- `|`: Union
- `in`, `contains`: Membership
- `&`: Intersection

#### Other Operators
- `.`: Navigation/invocation
- `[]`: Indexing

**Operator Precedence** (highest to lowest):
1. `.` (invocation)
2. `[]` (indexing)
3. Unary `-`, `+`
4. `*`, `/`, `div`, `mod`
5. `+`, `-`, `&` (string concatenation and collection operations)
6. `|`
7. `>`, `<`, `>=`, `<=`
8. `is`, `as`
9. `=`, `~`, `!=`, `!~`
10. `in`, `contains`
11. `and`
12. `or`, `xor`
13. `implies`

**Key Classes**:
- `OperatorRegistry`: Register and lookup operators
- `OperatorInterface`: Contract for all operators
- Individual operator implementations organized by category

### 6. Type System

**Purpose**: Implement FHIRPath type system aligned with FHIR types.

**Requirements**:
- Support for all FHIR primitive types
- Support for FHIR complex types
- Type hierarchy and inheritance
- Type conversion rules
- Type checking and validation
- Polymorphic type handling (e.g., `value[x]`)
- Quantity type with units
- Date/time types with precision

**FHIR Type Mapping**:
```
FHIRPath Type          FHIR Type
------------          ---------
System.String         string, code, id, uri, url, canonical, etc.
System.Boolean        boolean
System.Integer        integer, unsignedInt, positiveInt
System.Decimal        decimal
System.Date           date
System.DateTime       dateTime, instant
System.Time           time
System.Quantity       Quantity, SimpleQuantity
```

**Key Classes**:
- `FHIRPathTypeSystem`: Central type registry
- `TypeConverter`: Type conversion logic
- `TypeValidator`: Type validation
- `TypeInfo`: Type metadata

### 7. Service Layer

**Purpose**: Provide high-level API for FHIRPath evaluation.

**Requirements**:
- Simple evaluation API
- Expression compilation and caching
- Performance optimization
- Error handling and reporting
- Integration with FHIR resources
- Thread-safe operation
- Metrics and diagnostics

**Example Usage**:
```php
$service = new FHIRPathService();

// Simple evaluation
$result = $service->evaluate('Patient.name.given', $patientResource);

// With context
$context = new EvaluationContext();
$context->setRootResource($patientResource);
$result = $service->evaluateWithContext('name.where(use = "official").given', $context);

// Compiled expression (cached)
$expression = $service->compile('contact.telecom.where(system = "phone").value');
$result = $expression->evaluate($patientResource);
```

**Key Classes**:
- `FHIRPathService`: Main service API
- `FHIRPathCompiler`: Expression compilation
- `CompiledExpression`: Cached expression

## Dependencies

### Required Dependencies
```json
{
    "require": {
        "php": ">=8.2",
        "symfony/string": "^6.4|^7.4",
        "symfony/property-access": "^6.4|^7.4"
    }
}
```

### Optional Dependencies
```json
{
    "suggest": {
        "ardenexal/fhir-code-generation": "For FHIR type definitions",
        "ardenexal/fhir-serialization": "For FHIR resource serialization"
    }
}
```

### Development Dependencies
```json
{
    "require-dev": {
        "phpunit/phpunit": "^12.5",
        "giorgiosironi/eris": "^1.0"
    }
}
```

## Testing Requirements

### Unit Tests
- Parser unit tests (token recognition, AST construction)
- Evaluator unit tests (expression evaluation)
- Function unit tests (each function separately)
- Operator unit tests (each operator separately)
- Type system unit tests

### Integration Tests
- End-to-end expression evaluation
- FHIR resource navigation
- Complex queries
- Edge cases and error conditions

### Property-Based Tests
- Expression parsing properties
- Evaluation invariants
- Type system properties
- Collection behavior properties

### Compliance Tests
- FHIRPath 2.0 specification test suite
- FHIR test cases from official specification
- Cross-version compatibility tests

### Performance Tests
- Benchmark common queries
- Memory usage profiling
- Caching effectiveness
- Large resource handling

## Performance Considerations

### Optimization Strategies
1. **Expression Caching**: Cache parsed and compiled expressions
2. **Lazy Evaluation**: Evaluate only what's needed
3. **Short-Circuit Evaluation**: Stop early when possible
4. **Path Optimization**: Optimize common navigation patterns
5. **Collection Optimization**: Efficient collection operations
6. **Type Caching**: Cache type information
7. **Function Inlining**: Inline simple functions where possible

### Memory Management
- Avoid unnecessary object creation
- Stream large collections when possible
- Clear caches periodically
- Monitor memory usage

### Benchmarking Targets
- Simple path navigation: < 1ms
- Complex queries: < 10ms
- Large resources (>1MB): < 100ms
- Expression compilation: < 5ms

## Error Handling

### Error Categories
1. **Syntax Errors**: Invalid FHIRPath syntax
2. **Type Errors**: Type mismatches
3. **Evaluation Errors**: Runtime errors
4. **Resource Errors**: Invalid FHIR resources

### Error Information
- Clear error messages
- Expression context (position)
- Suggested fixes
- Stack traces for debugging

### Error Recovery
- Graceful degradation where possible
- Partial results when applicable
- Error collection for batch operations

## Documentation Requirements

### Code Documentation
- PHPDoc for all public methods
- Inline comments for complex logic
- Architecture decision records (ADRs)

### User Documentation
1. **README.md**: Quick start and overview
2. **Installation Guide**: Setup instructions
3. **Usage Guide**: Common use cases
4. **Function Reference**: All functions documented
5. **Type System Guide**: Type handling explanation
6. **Performance Guide**: Optimization tips
7. **Migration Guide**: Upgrading from other implementations

### Example Documentation
- Basic examples for all functions
- Complex query examples
- Integration examples
- Performance optimization examples

## Symfony Integration

### Bundle Integration
The FHIRPath component will integrate with FHIRBundle:

```yaml
# config/packages/fhir.yaml
fhir:
    fhir_path:
        cache_enabled: true
        cache_directory: '%kernel.cache_dir%/fhir_path'
        expression_cache_ttl: 3600
        enable_metrics: true
```

### Service Configuration
```yaml
# FHIRBundle/Resources/config/services.yaml
services:
    fhir.path_service:
        class: Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService
        public: true
        arguments:
            - '@fhir.path_parser'
            - '@fhir.path_evaluator'
            - '@fhir.path_compiler'
```

### Console Commands
```php
// Example command
php bin/console fhir:path:evaluate "Patient.name.given" patient.json
php bin/console fhir:path:validate expression.txt
php bin/console fhir:path:compile expressions.txt --output=compiled/
```

## Implementation Phases

### Phase 1: Core Parser and Lexer (2-3 weeks)
- [ ] Implement lexer with token recognition
- [ ] Implement recursive descent parser
- [ ] Build AST node hierarchy
- [ ] Add basic error handling
- [ ] Write parser unit tests

### Phase 2: Basic Evaluator (2-3 weeks)
- [ ] Implement evaluation context
- [ ] Implement path navigation
- [ ] Implement basic operators
- [ ] Add collection handling
- [ ] Write evaluator unit tests

### Phase 3: Function Library (3-4 weeks)
- [ ] Implement function registry
- [ ] Implement core functions (existence, filtering)
- [ ] Implement string functions
- [ ] Implement math functions
- [ ] Implement date/time functions
- [ ] Write function unit tests

### Phase 4: Type System (2 weeks)
- [ ] Implement type hierarchy
- [ ] Implement type conversion
- [ ] Implement type validation
- [ ] Add polymorphic type handling
- [ ] Write type system tests

### Phase 5: Advanced Features (2-3 weeks)
- [ ] Implement all remaining operators
- [ ] Add expression compilation
- [ ] Add caching
- [ ] Performance optimization
- [ ] Write integration tests

### Phase 6: Service Layer and Integration (1-2 weeks)
- [ ] Implement service API
- [ ] Integrate with FHIRBundle
- [ ] Add console commands
- [ ] Write integration tests
- [ ] Performance testing

### Phase 7: Documentation and Polish (1-2 weeks)
- [ ] Complete README and guides
- [ ] Add code examples
- [ ] Write function reference
- [ ] Create migration guide
- [ ] Final code review and cleanup

**Total Estimated Time**: 13-19 weeks (3-5 months)

## Success Criteria

1. **Specification Compliance**: Pass all FHIRPath 2.0 specification tests
2. **Performance**: Meet or exceed benchmarking targets
3. **Code Quality**: 
   - PSR-12 compliant
   - 90%+ code coverage
   - Zero PHPStan errors
4. **Documentation**: Complete user and API documentation
5. **Integration**: Seamless FHIRBundle integration
6. **Backward Compatibility**: No breaking changes to existing components

## References

- [FHIRPath 2.0 Specification](http://hl7.org/fhirpath/N1/)
- [FHIRPath 2.0 Grammar](http://hl7.org/fhirpath/N1/grammar.html)
- [FHIR R4B Specification](http://hl7.org/fhir/R4B/)
- [FHIRPath Test Cases](http://hl7.org/fhirpath/tests.html)

## Related Components

This component will integrate with:
- **CodeGeneration**: For FHIR type definitions
- **Serialization**: For resource handling
- **FHIRBundle**: For Symfony integration

## Future Enhancements

Potential future additions beyond initial implementation:
- FHIRPath 3.0 features (when specification released)
- Custom function extensions
- Query optimizer
- Visual expression builder
- IDE integration (language server)
- Browser-based playground
- Performance profiler
