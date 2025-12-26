# FHIRPath 2.0 Component - Implementation Tickets

This document contains GitHub issue tickets for each phase of the FHIRPath 2.0 component implementation. Copy and paste each ticket into GitHub Issues to track implementation progress.

---

## Phase 1: Project Setup (Week 1)

**Title:** FHIRPath Component - Phase 1: Project Setup

**Labels:** `component:fhirpath`, `phase:1`, `type:setup`

**Milestone:** FHIRPath 2.0 Implementation

**Description:**

Set up the foundational structure for the FHIRPath component following the established multi-component architecture.

### Tasks

- [ ] Create component directory structure
  - [ ] `src/Component/FHIRPath/src/`
  - [ ] `src/Component/FHIRPath/tests/`
  - [ ] Subdirectories: Parser, Evaluator, Expression, Function, Operator, Type, Service, Exception
  - [ ] Test subdirectories: Unit, Integration, Fixtures

- [ ] Create `composer.json` for standalone package
  - [ ] Package name: `ardenexal/fhir-path`
  - [ ] Dependencies: PHP 8.2+, symfony/string, symfony/property-access
  - [ ] Dev dependencies: phpunit/phpunit ^12.5, giorgiosironi/eris
  - [ ] PSR-4 autoloading configuration

- [ ] Create component README.md
  - [ ] Follow pattern of CodeGeneration and Serialization components
  - [ ] Include installation, quick start, and usage examples

- [ ] Set up testing infrastructure
  - [ ] Create `phpunit.xml` configuration
  - [ ] Set up test fixtures directory
  - [ ] Verify test runner works

- [ ] Create initial exception classes
  - [ ] `FHIRPathException` (base exception)
  - [ ] `ParseException`
  - [ ] `EvaluationException`

**Acceptance Criteria:**
- Component directory structure follows project conventions
- Composer package is properly configured
- README follows component template
- Tests can be run with `composer test`
- All files use `declare(strict_types=1)` and PSR-12 coding standards

**Documentation:**
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 1
- [Component Requirements](./component-guides/fhir-path.md)

**Estimated Time:** 1 week

---

## Phase 2: Lexer Implementation (Week 2)

**Title:** FHIRPath Component - Phase 2: Lexer Implementation

**Labels:** `component:fhirpath`, `phase:2`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase1-Issue-Number]

**Description:**

Implement the lexical analyzer (lexer) to tokenize FHIRPath expressions into a stream of tokens.

### Tasks

- [ ] Define TokenType enum
  - [ ] Literal tokens (STRING, NUMBER, BOOLEAN, NULL)
  - [ ] Keyword tokens (AND, OR, XOR, IMPLIES, AS, IS, etc.)
  - [ ] Operator tokens (EQUALS, NOT_EQUALS, GREATER_THAN, etc.)
  - [ ] Delimiter tokens (DOT, COMMA, LPAREN, RPAREN, etc.)
  - [ ] Special tokens (PIPE, AMPERSAND, IN, CONTAINS)
  - [ ] EOF token

- [ ] Implement Token class
  - [ ] Readonly class with type, value, line, column, position
  - [ ] Helper methods: `is()`, `isOneOf()`

- [ ] Implement FHIRPathLexer class
  - [ ] `tokenize()` method for complete tokenization
  - [ ] Token scanning with position tracking
  - [ ] String literal parsing with escape sequences
  - [ ] Number literal parsing (integer, decimal, scientific notation)
  - [ ] Identifier and keyword recognition
  - [ ] Operator recognition (single and multi-character)
  - [ ] DateTime and Time literal recognition
  - [ ] Quantity literal recognition
  - [ ] Whitespace and comment handling
  - [ ] Error reporting with line/column information

- [ ] Write comprehensive lexer tests
  - [ ] Test each token type individually
  - [ ] Test complex expressions
  - [ ] Test edge cases (empty strings, special characters)
  - [ ] Test error conditions (invalid tokens, unterminated strings)
  - [ ] Test position tracking accuracy

- [ ] Create TokenException class for lexer errors

**Acceptance Criteria:**
- All 40+ token types are recognized correctly
- Position tracking (line, column) is accurate
- String escape sequences are handled properly
- Error messages include helpful position information
- 90%+ test coverage for lexer
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Language Specification](./FHIRPATH_LANGUAGE_SPEC.md) - Lexical Elements
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 2

**Estimated Time:** 1 week

---

## Phase 3: Parser Implementation (Weeks 3-4)

**Title:** FHIRPath Component - Phase 3: Parser Implementation

**Labels:** `component:fhirpath`, `phase:3`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase2-Issue-Number]

**Description:**

Implement the recursive descent parser to convert token streams into Abstract Syntax Trees (AST).

### Tasks

- [ ] Define AST node hierarchy
  - [ ] `ExpressionNode` (base abstract class)
  - [ ] `LiteralNode` (string, number, boolean, null)
  - [ ] `PathNode` (navigation expressions)
  - [ ] `FunctionNode` (function invocations)
  - [ ] `OperatorNode` (binary and unary operators)
  - [ ] `InvocationNode` (member access)
  - [ ] Visitor pattern support for AST traversal

- [ ] Implement FHIRPathParser class
  - [ ] Recursive descent parsing algorithm
  - [ ] Expression parsing with operator precedence (13 levels)
  - [ ] Term parsing (logical operators)
  - [ ] Factor parsing (multiplicative operators)
  - [ ] Invocation parsing (member access, indexing)
  - [ ] Primary expression parsing (literals, identifiers, parentheses)
  - [ ] Function call parsing with parameters
  - [ ] Type expression parsing (is, as)
  - [ ] Collection literal parsing

- [ ] Implement operator precedence handling
  - [ ] Precedence table implementation
  - [ ] Left/right associativity handling
  - [ ] Unary operator handling

- [ ] Implement error recovery
  - [ ] Synchronization points for error recovery
  - [ ] Clear error messages with context
  - [ ] Multiple error collection

- [ ] Create SyntaxException class for parse errors

- [ ] Write comprehensive parser tests
  - [ ] Test each expression type
  - [ ] Test operator precedence
  - [ ] Test complex nested expressions
  - [ ] Test error cases (syntax errors, unexpected tokens)
  - [ ] Test edge cases (empty collections, parentheses)
  - [ ] Property-based tests for parser correctness

**Acceptance Criteria:**
- Parser handles complete FHIRPath grammar
- Operator precedence is correct (13 levels)
- AST structure is well-formed and traversable
- Error messages are helpful and include position info
- 90%+ test coverage for parser
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Language Specification](./FHIRPATH_LANGUAGE_SPEC.md) - Grammar
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 3

**Estimated Time:** 2-3 weeks

---

## Phase 4: Evaluator Implementation (Weeks 5-6)

**Title:** FHIRPath Component - Phase 4: Evaluator Implementation

**Labels:** `component:fhirpath`, `phase:4`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase3-Issue-Number]

**Description:**

Implement the expression evaluator to execute parsed AST against FHIR resources.

### Tasks

- [ ] Implement Collection class
  - [ ] Immutable collection abstraction
  - [ ] Support for empty, single, and multiple items
  - [ ] Iterator interface implementation
  - [ ] Helper methods: `isEmpty()`, `isSingle()`, `first()`, `last()`
  - [ ] Collection operations: `map()`, `filter()`, `union()`, `intersect()`

- [ ] Implement EvaluationContext class
  - [ ] Root resource tracking
  - [ ] Current node tracking
  - [ ] Variable storage ($this, $index, $total)
  - [ ] External constant support (%)
  - [ ] Path navigation state

- [ ] Implement FHIRPathEvaluator class
  - [ ] `evaluate()` method for AST nodes
  - [ ] Path navigation for FHIR resources
  - [ ] Literal evaluation
  - [ ] Operator evaluation (basic operators)
  - [ ] Function dispatch (to be completed in Phase 5)
  - [ ] Lazy evaluation support
  - [ ] Empty propagation handling

- [ ] Implement basic resource navigation
  - [ ] Simple property access
  - [ ] Nested property access
  - [ ] Array indexing
  - [ ] Polymorphic element handling (value[x])

- [ ] Create EvaluationException and TypeException classes

- [ ] Write evaluator tests
  - [ ] Test literal evaluation
  - [ ] Test path navigation
  - [ ] Test collection operations
  - [ ] Test empty propagation
  - [ ] Test context variables
  - [ ] Integration tests with sample FHIR resources

**Acceptance Criteria:**
- Basic expression evaluation works correctly
- Path navigation handles FHIR resource structure
- Collection semantics are correct
- Empty propagation follows specification
- 90%+ test coverage for evaluator
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Architecture](./architecture-fhirpath.md) - Evaluation Layer
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 4

**Estimated Time:** 2-3 weeks

---

## Phase 5: Function Library (Weeks 7-9)

**Title:** FHIRPath Component - Phase 5: Function Library

**Labels:** `component:fhirpath`, `phase:5`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase4-Issue-Number]

**Description:**

Implement the complete FHIRPath function library with 50+ standard functions across 8 categories.

### Tasks

- [ ] Create function infrastructure
  - [ ] `FunctionInterface` with execute method
  - [ ] `FunctionRegistry` for registration and lookup
  - [ ] Base abstract function class

- [ ] Implement Existence Functions (4 functions)
  - [ ] `empty()` - Check if collection is empty
  - [ ] `exists([criteria])` - Check if items exist matching criteria
  - [ ] `all(criteria)` - Check if all items match criteria
  - [ ] `allTrue()`, `anyTrue()`, `allFalse()`, `anyFalse()`

- [ ] Implement Filtering & Projection Functions (6 functions)
  - [ ] `where(criteria)` - Filter collection
  - [ ] `select(projection)` - Transform items
  - [ ] `repeat(projection)` - Recursive projection
  - [ ] `ofType(type)` - Filter by type
  - [ ] `first()`, `last()` - Get first/last item
  - [ ] `tail()`, `skip(num)`, `take(num)` - Collection slicing

- [ ] Implement String Functions (12 functions)
  - [ ] `substring(start, [length])` - Extract substring
  - [ ] `startsWith(prefix)`, `endsWith(suffix)` - String checks
  - [ ] `contains(substring)` - Substring check
  - [ ] `indexOf(substring)` - Find position
  - [ ] `upper()`, `lower()` - Case conversion
  - [ ] `replace(pattern, replacement)` - String replacement
  - [ ] `matches(regex)`, `replaceMatches(regex, replacement)` - Regex
  - [ ] `length()` - String length
  - [ ] `trim()` - Remove whitespace
  - [ ] `split(separator)` - String splitting

- [ ] Implement Math Functions (13 functions)
  - [ ] `abs()`, `ceiling()`, `floor()` - Basic math
  - [ ] `truncate()`, `round([precision])` - Rounding
  - [ ] `exp()`, `ln()`, `log(base)` - Exponentials
  - [ ] `power(exponent)`, `sqrt()` - Power functions
  - [ ] `sum()`, `min()`, `max()`, `avg()` - Aggregations

- [ ] Implement Date/Time Functions (6 functions)
  - [ ] `now()`, `timeOfDay()`, `today()` - Current date/time
  - [ ] `toMilliseconds()`, `toSeconds()` - Time conversion

- [ ] Implement Type Functions (8 functions)
  - [ ] `convertsToInteger()`, `convertsToDecimal()`, etc. - Conversion checks
  - [ ] `toInteger()`, `toDecimal()`, `toString()`, `toBoolean()` - Conversions
  - [ ] `toDate()`, `toDateTime()`, `toTime()` - Date conversions

- [ ] Implement Subsetting Functions
  - [ ] `single()` - Assert single item
  - [ ] `distinct()` - Remove duplicates
  - [ ] `isDistinct()` - Check distinctness
  - [ ] `intersect(other)`, `exclude(other)` - Set operations

- [ ] Implement Combining Functions
  - [ ] `union(other)` - Combine collections
  - [ ] `combine(other)` - Concatenate
  - [ ] `iif(condition, true-result, false-result)` - Conditional

- [ ] Write comprehensive function tests
  - [ ] Test each function individually
  - [ ] Test edge cases (empty collections, null values)
  - [ ] Test parameter validation
  - [ ] Integration tests with complex expressions

**Acceptance Criteria:**
- All 50+ functions implemented correctly
- Functions follow FHIRPath 2.0 specification
- Each function has comprehensive tests
- Function registry works correctly
- 90%+ test coverage for all functions
- All tests pass
- Documentation includes examples for each function

**Documentation:**
- [Component Requirements](./component-guides/fhir-path.md) - Function Library
- [Language Specification](./FHIRPATH_LANGUAGE_SPEC.md) - Functions Reference
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 5

**Estimated Time:** 3-4 weeks

---

## Phase 6: Operator Implementation (Weeks 10-11)

**Title:** FHIRPath Component - Phase 6: Operator Implementation

**Labels:** `component:fhirpath`, `phase:6`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase5-Issue-Number]

**Description:**

Implement all FHIRPath operators with correct precedence and semantics.

### Tasks

- [ ] Create operator infrastructure
  - [ ] `OperatorInterface` with execute method
  - [ ] `OperatorRegistry` with precedence support
  - [ ] Base abstract operator class

- [ ] Implement Comparison Operators
  - [ ] `=`, `!=` - Equality (with null propagation)
  - [ ] `~`, `!~` - Equivalence (type-aware)
  - [ ] `>`, `<`, `>=`, `<=` - Ordering

- [ ] Implement Logical Operators
  - [ ] `and` - Logical AND (three-valued logic)
  - [ ] `or` - Logical OR (three-valued logic)
  - [ ] `xor` - Exclusive OR
  - [ ] `implies` - Logical implication

- [ ] Implement Mathematical Operators
  - [ ] `+`, `-` - Addition, subtraction
  - [ ] `*`, `/` - Multiplication, division
  - [ ] `div` - Integer division
  - [ ] `mod` - Modulus
  - [ ] Unary `+`, `-` - Positive, negation

- [ ] Implement Collection Operators
  - [ ] `|` - Union
  - [ ] `&` - Intersection
  - [ ] `in` - Membership test
  - [ ] `contains` - Contains test

- [ ] Implement Type Operators
  - [ ] `is` - Type checking
  - [ ] `as` - Type casting

- [ ] Implement operator precedence
  - [ ] 13 precedence levels
  - [ ] Left/right associativity
  - [ ] Integration with parser

- [ ] Implement three-valued logic
  - [ ] Truth tables for logical operators
  - [ ] Empty propagation rules

- [ ] Write comprehensive operator tests
  - [ ] Test each operator individually
  - [ ] Test operator precedence combinations
  - [ ] Test three-valued logic
  - [ ] Test edge cases (empty collections, type mismatches)
  - [ ] Property-based tests for operator laws

**Acceptance Criteria:**
- All 20+ operators implemented correctly
- Operator precedence matches specification
- Three-valued logic is correct
- Empty propagation follows specification
- 90%+ test coverage for operators
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Language Specification](./FHIRPATH_LANGUAGE_SPEC.md) - Operator Semantics
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 6

**Estimated Time:** 2-3 weeks

---

## Phase 7: Type System (Weeks 12-13)

**Title:** FHIRPath Component - Phase 7: Type System

**Labels:** `component:fhirpath`, `phase:7`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase6-Issue-Number]

**Description:**

Implement the FHIRPath type system aligned with FHIR data types.

### Tasks

- [ ] Implement FHIRPathTypeSystem class
  - [ ] Type hierarchy management
  - [ ] System types (Boolean, String, Integer, Decimal, Date, DateTime, Time, Quantity)
  - [ ] FHIR type integration (Resource, Element, all FHIR types)
  - [ ] Type compatibility checking
  - [ ] Type hierarchy queries

- [ ] Implement TypeConverter class
  - [ ] Conversion between system types
  - [ ] String parsing to typed values
  - [ ] Numeric conversions
  - [ ] Date/time conversions
  - [ ] Quantity conversions with units

- [ ] Implement TypeValidator class
  - [ ] Type validation methods
  - [ ] Type checking for each system type
  - [ ] FHIR type validation

- [ ] Implement TypeInfo class
  - [ ] Type metadata storage
  - [ ] Type hierarchy information
  - [ ] Conversion rules

- [ ] Implement polymorphic type handling
  - [ ] Support for FHIR polymorphic elements (value[x])
  - [ ] Type resolution for polymorphic access
  - [ ] Type filtering

- [ ] Integrate type system with evaluator
  - [ ] Type checking during evaluation
  - [ ] Implicit type conversions
  - [ ] Type casting (as operator)
  - [ ] Type filtering (ofType function)

- [ ] Write type system tests
  - [ ] Test type hierarchy
  - [ ] Test type conversions
  - [ ] Test type validation
  - [ ] Test polymorphic types
  - [ ] Test type system integration
  - [ ] Property-based tests for conversion laws

**Acceptance Criteria:**
- Type system handles all FHIRPath and FHIR types
- Type conversions follow specification
- Polymorphic types work correctly
- Type checking is accurate
- 90%+ test coverage for type system
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Language Specification](./FHIRPATH_LANGUAGE_SPEC.md) - Type System
- [Architecture](./architecture-fhirpath.md) - Type System
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 7

**Estimated Time:** 2 weeks

---

## Phase 8: Service Layer (Week 14)

**Title:** FHIRPath Component - Phase 8: Service Layer

**Labels:** `component:fhirpath`, `phase:8`, `type:feature`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase7-Issue-Number]

**Description:**

Implement the high-level service API for FHIRPath evaluation with compilation and caching.

### Tasks

- [ ] Implement FHIRPathService class
  - [ ] Simple `evaluate()` API
  - [ ] `compile()` method for expression caching
  - [ ] `validate()` method for syntax checking
  - [ ] Error handling and reporting
  - [ ] Thread-safe operation

- [ ] Implement FHIRPathCompiler class
  - [ ] Expression compilation
  - [ ] AST optimization
  - [ ] Metadata generation

- [ ] Implement CompiledExpression class
  - [ ] Cached AST storage
  - [ ] Fast evaluation method
  - [ ] Metadata access

- [ ] Implement ExpressionCache class
  - [ ] LRU cache implementation
  - [ ] Cache key generation
  - [ ] Cache statistics
  - [ ] Configurable TTL

- [ ] Implement optimization passes
  - [ ] Constant folding
  - [ ] Path simplification
  - [ ] Dead code elimination

- [ ] Create service interfaces
  - [ ] `FHIRPathServiceInterface`
  - [ ] `CompilerInterface`
  - [ ] `CacheInterface`

- [ ] Write integration tests
  - [ ] Test complete evaluation workflows
  - [ ] Test compilation and caching
  - [ ] Test error handling
  - [ ] Performance tests
  - [ ] Thread safety tests

**Acceptance Criteria:**
- Service API is simple and intuitive
- Expression compilation works correctly
- Caching improves performance (target: <0.1ms for cached)
- Error messages are helpful
- 90%+ test coverage
- All tests pass
- Code follows PSR-12 and uses strict types

**Documentation:**
- [Architecture](./architecture-fhirpath.md) - Service Layer
- [Component Requirements](./component-guides/fhir-path.md) - Service Layer
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 8

**Estimated Time:** 1 week

---

## Phase 9: Optimization (Week 15)

**Title:** FHIRPath Component - Phase 9: Optimization

**Labels:** `component:fhirpath`, `phase:9`, `type:enhancement`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase8-Issue-Number]

**Description:**

Profile and optimize the FHIRPath component to meet performance targets.

### Tasks

- [ ] Profile performance
  - [ ] Identify bottlenecks in lexer
  - [ ] Identify bottlenecks in parser
  - [ ] Identify bottlenecks in evaluator
  - [ ] Identify bottlenecks in functions
  - [ ] Memory usage profiling

- [ ] Implement lexer optimizations
  - [ ] Token buffer optimization
  - [ ] String interning for keywords
  - [ ] Fast path for common tokens

- [ ] Implement parser optimizations
  - [ ] Operator precedence table optimization
  - [ ] AST node pooling
  - [ ] Reduce object allocations

- [ ] Implement evaluator optimizations
  - [ ] Lazy evaluation improvements
  - [ ] Short-circuit evaluation
  - [ ] Collection streaming for large datasets
  - [ ] Path caching

- [ ] Implement function optimizations
  - [ ] Inline simple functions
  - [ ] Function result caching
  - [ ] Optimize common function patterns

- [ ] Memory optimizations
  - [ ] Reduce object creation
  - [ ] Use weak references where appropriate
  - [ ] Implement object pooling

- [ ] Write performance benchmarks
  - [ ] Benchmark simple paths (<1ms target)
  - [ ] Benchmark complex queries (<10ms target)
  - [ ] Benchmark large resources (<100ms target)
  - [ ] Benchmark compilation (<5ms target)
  - [ ] Benchmark cache hits (<0.1ms target)
  - [ ] Memory usage benchmarks

- [ ] Document optimization results
  - [ ] Before/after comparisons
  - [ ] Performance recommendations
  - [ ] Memory usage guidelines

**Acceptance Criteria:**
- Simple paths evaluate in <1ms
- Complex queries evaluate in <10ms
- Large resources (>1MB) evaluate in <100ms
- Compilation takes <5ms
- Cache hits take <0.1ms
- Memory usage is reasonable
- All existing tests still pass
- Performance benchmarks are documented

**Documentation:**
- [Architecture](./architecture-fhirpath.md) - Performance Optimization
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 9

**Estimated Time:** 1 week

---

## Phase 10: Documentation (Week 16)

**Title:** FHIRPath Component - Phase 10: Documentation

**Labels:** `component:fhirpath`, `phase:10`, `type:documentation`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase9-Issue-Number]

**Description:**

Complete all user-facing documentation for the FHIRPath component.

### Tasks

- [ ] Complete component README.md
  - [ ] Installation instructions
  - [ ] Quick start guide
  - [ ] Basic usage examples
  - [ ] Advanced usage examples
  - [ ] Configuration options
  - [ ] Performance tips

- [ ] Write comprehensive usage guide
  - [ ] Getting started tutorial
  - [ ] Common use cases
  - [ ] Best practices
  - [ ] Troubleshooting
  - [ ] FAQ

- [ ] Write complete function reference
  - [ ] Document all 50+ functions
  - [ ] Parameter descriptions
  - [ ] Return value descriptions
  - [ ] Usage examples for each function
  - [ ] Edge case documentation

- [ ] Create practical examples
  - [ ] Patient data queries
  - [ ] Observation filtering
  - [ ] Resource validation
  - [ ] Complex query examples
  - [ ] Performance optimization examples

- [ ] Write API documentation
  - [ ] Complete PHPDoc for all public methods
  - [ ] Interface documentation
  - [ ] Exception documentation
  - [ ] Type system documentation

- [ ] Create migration guide
  - [ ] Comparison with other implementations
  - [ ] Migration from fhirpath.js
  - [ ] Migration from FHIRPath.NET
  - [ ] Breaking changes (if any)

- [ ] Add inline code documentation
  - [ ] Complex algorithm explanations
  - [ ] Performance considerations
  - [ ] Edge case handling

- [ ] Review and update existing documentation
  - [ ] Architecture documentation
  - [ ] Implementation guide
  - [ ] Language specification

**Acceptance Criteria:**
- README is comprehensive and easy to follow
- All functions are documented with examples
- API documentation is complete
- Examples are practical and runnable
- Documentation follows project conventions
- Links between documents work correctly
- Code examples are tested and accurate

**Documentation:**
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 10

**Estimated Time:** 1 week

---

## Phase 11: Integration (Week 17)

**Title:** FHIRPath Component - Phase 11: FHIRBundle Integration

**Labels:** `component:fhirpath`, `phase:11`, `type:integration`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase10-Issue-Number]

**Description:**

Integrate the FHIRPath component with FHIRBundle for Symfony applications.

### Tasks

- [ ] Update FHIRBundle configuration
  - [ ] Add FHIRPath service registration
  - [ ] Add configuration options (cache, performance settings)
  - [ ] Add service aliases

- [ ] Create Symfony service definitions
  - [ ] Register FHIRPathService
  - [ ] Register FHIRPathParser
  - [ ] Register FHIRPathEvaluator
  - [ ] Register FHIRPathCompiler
  - [ ] Configure dependency injection

- [ ] Create console commands
  - [ ] `fhir:path:evaluate` - Evaluate expressions
  - [ ] `fhir:path:validate` - Validate syntax
  - [ ] `fhir:path:compile` - Compile expressions
  - [ ] Add command documentation

- [ ] Add FHIRBundle configuration
  ```yaml
  fhir:
      fhir_path:
          cache_enabled: true
          cache_directory: '%kernel.cache_dir%/fhir_path'
          expression_cache_ttl: 3600
          enable_metrics: true
  ```

- [ ] Update FHIRBundle README
  - [ ] Add FHIRPath usage examples
  - [ ] Document configuration options
  - [ ] Add console command examples

- [ ] Create Symfony Flex recipe
  - [ ] Default configuration
  - [ ] Service registration
  - [ ] Directory structure

- [ ] Write integration tests
  - [ ] Test service registration
  - [ ] Test dependency injection
  - [ ] Test console commands
  - [ ] Test configuration loading
  - [ ] Test with sample Symfony application

**Acceptance Criteria:**
- FHIRPath services are properly registered in FHIRBundle
- Console commands work correctly
- Configuration options are functional
- Dependency injection works as expected
- Integration tests pass
- Documentation is complete
- Code follows PSR-12 and Symfony best practices

**Documentation:**
- [Component Requirements](./component-guides/fhir-path.md) - Symfony Integration
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 11

**Estimated Time:** 1 week

---

## Phase 12: Final Review (Week 18)

**Title:** FHIRPath Component - Phase 12: Final Review and Release

**Labels:** `component:fhirpath`, `phase:12`, `type:review`

**Milestone:** FHIRPath 2.0 Implementation

**Depends On:** #[Phase11-Issue-Number]

**Description:**

Perform final code review, testing, and preparation for release.

### Tasks

- [ ] Comprehensive code review
  - [ ] Review all component code
  - [ ] Check PSR-12 compliance
  - [ ] Verify strict types everywhere
  - [ ] Review PHPDoc completeness
  - [ ] Check for code smells
  - [ ] Review error handling

- [ ] Run complete test suite
  - [ ] Run all unit tests
  - [ ] Run all integration tests
  - [ ] Run property-based tests
  - [ ] Verify 90%+ code coverage
  - [ ] Run compliance tests against FHIRPath specification
  - [ ] Fix any failing tests

- [ ] Performance verification
  - [ ] Run all performance benchmarks
  - [ ] Verify performance targets met
  - [ ] Document performance results
  - [ ] Identify any performance regressions

- [ ] Security review
  - [ ] Check for security vulnerabilities
  - [ ] Review input validation
  - [ ] Check error message safety
  - [ ] Review resource limits
  - [ ] Run security scanning tools

- [ ] Documentation review
  - [ ] Review all documentation for accuracy
  - [ ] Check all links work
  - [ ] Verify code examples are correct
  - [ ] Ensure documentation is complete
  - [ ] Check for typos and grammar

- [ ] Run static analysis
  - [ ] Run PHPStan at level 9
  - [ ] Run PHP-CS-Fixer
  - [ ] Fix any issues found

- [ ] Backward compatibility check
  - [ ] Verify no breaking changes to other components
  - [ ] Test integration with CodeGeneration component
  - [ ] Test integration with Serialization component

- [ ] Prepare release
  - [ ] Update CHANGELOG.md
  - [ ] Tag release version
  - [ ] Create release notes
  - [ ] Prepare announcement

- [ ] Final checklist review
  - [ ] ✅ All tests pass (90%+ coverage)
  - [ ] ✅ PSR-12 compliant
  - [ ] ✅ PHPStan level 9 passes
  - [ ] ✅ Performance targets met
  - [ ] ✅ Documentation complete
  - [ ] ✅ Integration working
  - [ ] ✅ No security issues
  - [ ] ✅ No breaking changes

**Acceptance Criteria:**
- All tests pass with 90%+ coverage
- Code quality checks pass (PSR-12, PHPStan level 9)
- Performance targets are met
- Documentation is complete and accurate
- No security vulnerabilities
- No breaking changes to other components
- Ready for production use

**Documentation:**
- [Implementation Guide](./FHIRPATH_IMPLEMENTATION_GUIDE.md) - Phase 12
- [Quick Reference](./FHIRPATH_QUICK_REFERENCE.md) - Success Criteria

**Estimated Time:** 1 week

---

## Summary

**Total Phases:** 12  
**Total Estimated Time:** 13-19 weeks (3-5 months)  
**Target Test Coverage:** 90%+  
**Target Performance:** <1ms simple paths, <10ms complex queries

### Phase Dependencies

```
Phase 1 (Setup)
    └── Phase 2 (Lexer)
        └── Phase 3 (Parser)
            └── Phase 4 (Evaluator)
                └── Phase 5 (Functions)
                    └── Phase 6 (Operators)
                        └── Phase 7 (Type System)
                            └── Phase 8 (Service Layer)
                                └── Phase 9 (Optimization)
                                    └── Phase 10 (Documentation)
                                        └── Phase 11 (Integration)
                                            └── Phase 12 (Final Review)
```

### Labels to Create

- `component:fhirpath`
- `phase:1` through `phase:12`
- `type:setup`
- `type:feature`
- `type:enhancement`
- `type:documentation`
- `type:integration`
- `type:review`

### Milestone

Create a milestone: **FHIRPath 2.0 Implementation** with target completion date based on team capacity.

---

## Instructions for Use

1. Create the milestone "FHIRPath 2.0 Implementation" in GitHub
2. Create the labels listed above
3. For each phase, create a new issue by copying the content
4. Add the appropriate labels and milestone
5. Link dependent issues (update #[PhaseN-Issue-Number] placeholders)
6. Assign team members as appropriate
7. Track progress through the GitHub Issues board

**Note:** Each phase should be completed and reviewed before starting the next phase to ensure quality and maintainability.
