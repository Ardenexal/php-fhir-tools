# FHIRPath Component Implementation Guide

## Getting Started

This guide provides a roadmap for implementing the FHIRPath 2.0 component for PHP FHIRTools.

## Prerequisites

Before starting implementation:

1. **Read the Specifications**:
   - [FHIRPath 2.0 Specification](http://hl7.org/fhirpath/N1/)
   - [FHIRPath Grammar](http://hl7.org/fhirpath/N1/grammar.html)
   - [FHIR R4B Specification](http://hl7.org/fhir/R4B/)

2. **Understand the Architecture**:
   - Review `docs/architecture.md`
   - Review `docs/architecture-fhirpath.md`
   - Study existing components (CodeGeneration, Serialization)

3. **Set Up Development Environment**:
   ```bash
   # Clone repository
   git clone https://github.com/Ardenexal/php-fhir-tools.git
   cd php-fhir-tools
   
   # Install dependencies
   composer install
   
   # Run tests to ensure everything works
   composer run test
   ```

## Implementation Phases

### Phase 1: Project Setup (Week 1)

#### 1.1 Create Component Structure

```bash
mkdir -p src/Component/FHIRPath/{src,tests}
mkdir -p src/Component/FHIRPath/src/{Parser,Evaluator,Expression,Function,Operator,Type,Service,Exception}
mkdir -p src/Component/FHIRPath/tests/{Unit,Integration,Fixtures}
```

#### 1.2 Create composer.json

Create `src/Component/FHIRPath/composer.json`:

```json
{
    "name": "ardenexal/fhir-path",
    "description": "FHIRPath 2.0 expression evaluation for PHP",
    "type": "library",
    "license": "MIT",
    "require": {
        "php": ">=8.2",
        "symfony/string": "^6.4|^7.4",
        "symfony/property-access": "^6.4|^7.4"
    },
    "require-dev": {
        "phpunit/phpunit": "^12.5",
        "giorgiosironi/eris": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "Ardenexal\\FHIRTools\\Component\\FHIRPath\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Ardenexal\\FHIRTools\\Component\\FHIRPath\\Tests\\": "tests/"
        }
    }
}
```

#### 1.3 Create README.md

Create `src/Component/FHIRPath/README.md` following the pattern of other components.

#### 1.4 Set Up Testing Infrastructure

Create `src/Component/FHIRPath/phpunit.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/12.5/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="FHIRPath Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <report>
            <html outputDirectory="coverage"/>
        </report>
    </coverage>
</phpunit>
```

### Phase 2: Lexer Implementation (Week 2)

#### 2.1 Define Token Types

Create `src/Component/FHIRPath/src/Parser/TokenType.php`:

```php
<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

/**
 * FHIRPath token types
 */
enum TokenType: string
{
    // Literals
    case STRING = 'STRING';
    case NUMBER = 'NUMBER';
    case BOOLEAN = 'BOOLEAN';
    case NULL = 'NULL';
    
    // Identifiers and Keywords
    case IDENTIFIER = 'IDENTIFIER';
    case AND = 'AND';
    case OR = 'OR';
    case XOR = 'XOR';
    case IMPLIES = 'IMPLIES';
    case AS = 'AS';
    case IS = 'IS';
    
    // Operators
    case EQUALS = 'EQUALS';                  // =
    case NOT_EQUALS = 'NOT_EQUALS';          // !=
    case EQUIVALENT = 'EQUIVALENT';          // ~
    case NOT_EQUIVALENT = 'NOT_EQUIVALENT';  // !~
    case GREATER_THAN = 'GREATER_THAN';      // >
    case LESS_THAN = 'LESS_THAN';            // <
    case GREATER_EQUAL = 'GREATER_EQUAL';    // >=
    case LESS_EQUAL = 'LESS_EQUAL';          // <=
    
    // Arithmetic
    case PLUS = 'PLUS';                      // +
    case MINUS = 'MINUS';                    // -
    case MULTIPLY = 'MULTIPLY';              // *
    case DIVIDE = 'DIVIDE';                  // /
    case DIV = 'DIV';                        // div
    case MOD = 'MOD';                        // mod
    
    // Delimiters
    case DOT = 'DOT';                        // .
    case COMMA = 'COMMA';                    // ,
    case LPAREN = 'LPAREN';                  // (
    case RPAREN = 'RPAREN';                  // )
    case LBRACKET = 'LBRACKET';              // [
    case RBRACKET = 'RBRACKET';              // ]
    case LBRACE = 'LBRACE';                  // {
    case RBRACE = 'RBRACE';                  // }
    
    // Special
    case PIPE = 'PIPE';                      // |
    case AMPERSAND = 'AMPERSAND';            // &
    case IN = 'IN';                          // in
    case CONTAINS = 'CONTAINS';              // contains
    
    // End
    case EOF = 'EOF';
}
```

#### 2.2 Implement Token Class

Create `src/Component/FHIRPath/src/Parser/Token.php`:

```php
<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

/**
 * Represents a token in FHIRPath expression
 */
readonly class Token
{
    public function __construct(
        public TokenType $type,
        public string $value,
        public int $line,
        public int $column,
        public int $position
    ) {
    }
    
    public function is(TokenType $type): bool
    {
        return $this->type === $type;
    }
    
    public function isOneOf(TokenType ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->type === $type) {
                return true;
            }
        }
        return false;
    }
}
```

#### 2.3 Implement Lexer

Create `src/Component/FHIRPath/src/Parser/FHIRPathLexer.php`:

```php
<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\TokenException;

/**
 * Tokenizes FHIRPath expressions
 */
class FHIRPathLexer
{
    private string $input;
    private int $position = 0;
    private int $line = 1;
    private int $column = 1;
    private array $tokens = [];
    
    public function tokenize(string $expression): array
    {
        $this->input = $expression;
        $this->position = 0;
        $this->line = 1;
        $this->column = 1;
        $this->tokens = [];
        
        while (!$this->isAtEnd()) {
            $this->scanToken();
        }
        
        $this->tokens[] = new Token(
            TokenType::EOF,
            '',
            $this->line,
            $this->column,
            $this->position
        );
        
        return $this->tokens;
    }
    
    private function scanToken(): void
    {
        $this->skipWhitespace();
        
        if ($this->isAtEnd()) {
            return;
        }
        
        $start = $this->position;
        $startLine = $this->line;
        $startColumn = $this->column;
        
        $char = $this->advance();
        
        // Implementation of token scanning...
        // This is a simplified structure
    }
    
    private function skipWhitespace(): void
    {
        while (!$this->isAtEnd()) {
            $char = $this->peek();
            if ($char === ' ' || $char === "\t" || $char === "\r" || $char === "\n") {
                $this->advance();
            } else {
                break;
            }
        }
    }
    
    private function advance(): string
    {
        if ($this->isAtEnd()) {
            return "\0";
        }
        
        $char = $this->input[$this->position++];
        
        if ($char === "\n") {
            $this->line++;
            $this->column = 1;
        } else {
            $this->column++;
        }
        
        return $char;
    }
    
    private function peek(int $offset = 0): string
    {
        $pos = $this->position + $offset;
        if ($pos >= strlen($this->input)) {
            return "\0";
        }
        return $this->input[$pos];
    }
    
    private function isAtEnd(): bool
    {
        return $this->position >= strlen($this->input);
    }
}
```

#### 2.4 Write Lexer Tests

Create `src/Component/FHIRPath/tests/Unit/Parser/FHIRPathLexerTest.php`:

```php
<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

class FHIRPathLexerTest extends TestCase
{
    private FHIRPathLexer $lexer;
    
    protected function setUp(): void
    {
        $this->lexer = new FHIRPathLexer();
    }
    
    public function testTokenizeSimpleIdentifier(): void
    {
        $tokens = $this->lexer->tokenize('name');
        
        self::assertCount(2, $tokens); // identifier + EOF
        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('name', $tokens[0]->value);
        self::assertEquals(TokenType::EOF, $tokens[1]->type);
    }
    
    // More tests...
}
```

### Phase 3: Parser Implementation (Weeks 3-4)

#### 3.1 Define AST Node Hierarchy

Create base expression node and specialized nodes for different expression types.

#### 3.2 Implement Recursive Descent Parser

Implement parser with proper operator precedence and error recovery.

#### 3.3 Write Parser Tests

Test all expression types, error cases, and edge conditions.

### Phase 4: Evaluator Implementation (Weeks 5-6)

#### 4.1 Implement Collection Class

Create collection abstraction for FHIRPath values.

#### 4.2 Implement Evaluation Context

Create context for maintaining state during evaluation.

#### 4.3 Implement Basic Evaluator

Start with simple path navigation and literals.

#### 4.4 Write Evaluator Tests

Test evaluation of various expressions.

### Phase 5: Function Library (Weeks 7-9)

#### 5.1 Create Function Registry

Implement function registration and lookup.

#### 5.2 Implement Core Functions

Start with existence and filtering functions.

#### 5.3 Implement String Functions

Add all string manipulation functions.

#### 5.4 Implement Math Functions

Add mathematical and aggregation functions.

#### 5.5 Implement Date/Time Functions

Add date and time manipulation functions.

#### 5.6 Write Function Tests

Test each function individually.

### Phase 6: Operator Implementation (Weeks 10-11)

#### 6.1 Create Operator Registry

Implement operator registration with precedence.

#### 6.2 Implement All Operators

Comparison, logical, mathematical, and collection operators.

#### 6.3 Write Operator Tests

Test each operator with various inputs.

### Phase 7: Type System (Weeks 12-13)

#### 7.1 Implement Type Hierarchy

Define FHIR type hierarchy.

#### 7.2 Implement Type Conversion

Add conversion between types.

#### 7.3 Implement Type Validation

Add type checking logic.

#### 7.4 Write Type System Tests

Test type operations.

### Phase 8: Service Layer (Week 14)

#### 8.1 Implement FHIRPath Service

Create high-level API.

#### 8.2 Implement Compiler

Add expression compilation.

#### 8.3 Implement Caching

Add expression cache.

#### 8.4 Write Integration Tests

Test complete workflows.

### Phase 9: Optimization (Week 15)

#### 9.1 Profile Performance

Identify bottlenecks.

#### 9.2 Implement Optimizations

Add performance improvements.

#### 9.3 Write Performance Tests

Add benchmarks.

### Phase 10: Documentation (Week 16)

#### 10.1 Complete README

Write comprehensive README.

#### 10.2 Write Usage Guide

Add examples and use cases.

#### 10.3 Write Function Reference

Document all functions.

#### 10.4 Create Examples

Add practical examples.

### Phase 11: Integration (Week 17)

#### 11.1 Integrate with FHIRBundle

Add service registration.

#### 11.2 Create Console Commands

Add CLI tools.

#### 11.3 Write Integration Tests

Test bundle integration.

### Phase 12: Final Review (Week 18)

#### 12.1 Code Review

Review all code for quality.

#### 12.2 Run All Tests

Ensure 90%+ coverage.

#### 12.3 Performance Testing

Verify performance targets.

#### 12.4 Documentation Review

Complete all documentation.

## Development Guidelines

### Coding Standards

1. **Strict Types**: Always use `declare(strict_types=1);`
2. **Type Hints**: Use type hints for all parameters and returns
3. **PSR-12**: Follow PSR-12 coding standard
4. **PHPDoc**: Document all public methods
5. **Immutability**: Prefer readonly properties and immutable objects

### Testing Standards

1. **Coverage**: Aim for 90%+ code coverage
2. **Unit Tests**: Test individual components
3. **Integration Tests**: Test component interactions
4. **Property Tests**: Test invariants with Eris
5. **Assertions**: Use `self::assert*` not `$this->assert*`

### Performance Standards

1. **Benchmarking**: Profile all critical paths
2. **Memory**: Monitor memory usage
3. **Caching**: Cache expensive operations
4. **Lazy Evaluation**: Defer work when possible

### Documentation Standards

1. **README**: Clear, comprehensive documentation
2. **Examples**: Practical, runnable examples
3. **API Docs**: Complete PHPDoc for all public APIs
4. **Guides**: Step-by-step guides for common tasks

## Testing Strategy

### Unit Tests

```php
public function testExistsFunction(): void
{
    $function = new ExistsFunction();
    $collection = new Collection([1, 2, 3]);
    $result = $function->execute($collection, [], $context);
    
    self::assertTrue($result->single());
}
```

### Integration Tests

```php
public function testComplexQuery(): void
{
    $service = new FHIRPathService();
    $patient = $this->loadTestResource('patient.json');
    
    $result = $service->evaluate(
        'name.where(use = "official").given.first()',
        $patient
    );
    
    self::assertEquals('John', $result->single());
}
```

### Property Tests

```php
public function testCollectionUnionIsCommutative(): void
{
    $this->forAll(
        Generator\seq(Generator\int()),
        Generator\seq(Generator\int())
    )->then(function (array $a, array $b) {
        $collA = new Collection($a);
        $collB = new Collection($b);
        
        $union1 = $collA->union($collB);
        $union2 = $collB->union($collA);
        
        // Union should be commutative
        self::assertEquals($union1->toArray(), $union2->toArray());
    });
}
```

## Common Pitfalls

1. **Operator Precedence**: Ensure correct precedence handling
2. **Null Handling**: FHIRPath has specific null semantics
3. **Collection Semantics**: Everything is a collection
4. **Type Conversion**: Implicit conversions must follow spec
5. **Error Messages**: Provide helpful error messages with context

## Resources

### Essential Reading

- [FHIRPath 2.0 Specification](http://hl7.org/fhirpath/N1/)
- [FHIRPath Grammar](http://hl7.org/fhirpath/N1/grammar.html)
- [FHIRPath Test Suite](http://hl7.org/fhirpath/tests.html)

### Reference Implementations

- [fhirpath.js](https://github.com/HL7/fhirpath.js) (JavaScript)
- [FHIRPath.NET](https://github.com/FirelyTeam/fhirpath.net) (C#)

### Project Documentation

- [Architecture Guide](../architecture.md)
- [FHIRPath Architecture](../architecture-fhirpath.md)
- [Component Requirements](./fhir-path.md)
- [AGENTS.md](../../AGENTS.md)

## Getting Help

1. Review existing component implementations
2. Check FHIRPath specification
3. Look at reference implementations
4. Consult team members

## Checklist

Before submitting your work:

- [ ] All tests pass (`composer run test`)
- [ ] Code style is correct (`composer run lint`)
- [ ] PHPStan passes (`composer run phpstan`)
- [ ] Coverage is 90%+
- [ ] Documentation is complete
- [ ] Examples work correctly
- [ ] Performance targets met
- [ ] Integration tests pass
- [ ] Bundle integration works
- [ ] No breaking changes

## Next Steps

1. Set up project structure
2. Implement lexer
3. Implement parser
4. Implement evaluator
5. Continue through all phases

Good luck with the implementation!
