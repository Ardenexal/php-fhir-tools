# FHIRPath 2.0 Component - Quick Reference

## 📋 Overview

The FHIRPath component will enable evaluation of FHIRPath 2.0 expressions against FHIR resources. This is a standalone library that integrates with PHP FHIRTools.

## 🎯 Key Goals

1. **FHIRPath 2.0 Compliance**: Full implementation of the FHIRPath 2.0 specification
2. **Performance**: Fast expression evaluation with caching
3. **Integration**: Seamless integration with FHIRBundle and existing components
4. **Usability**: Simple, intuitive API for developers

## 📁 Component Structure

```
src/Component/FHIRPath/
├── src/
│   ├── Parser/              # Lexer, Parser, AST
│   ├── Evaluator/           # Expression evaluation
│   ├── Expression/          # AST node types
│   ├── Function/            # 50+ built-in functions
│   ├── Operator/            # All FHIRPath operators
│   ├── Type/                # Type system
│   ├── Service/             # Public API
│   └── Exception/           # Error handling
├── tests/                   # Comprehensive test suite
├── composer.json           
└── README.md
```

## 🔧 Core Components

### 1. Lexer & Parser
**Purpose**: Convert string expressions to Abstract Syntax Trees (AST)

```php
// Input: "Patient.name.given.first()"
// Output: AST structure representing the expression
$lexer = new FHIRPathLexer();
$tokens = $lexer->tokenize($expression);

$parser = new FHIRPathParser();
$ast = $parser->parse($tokens);
```

### 2. Evaluator
**Purpose**: Execute AST against FHIR resources

```php
$evaluator = new FHIRPathEvaluator();
$result = $evaluator->evaluate($ast, $resource);
```

### 3. Functions (50+ total)
**Categories**:
- Existence (4): `empty()`, `exists()`, `all()`, etc.
- Filtering (6): `where()`, `select()`, `first()`, etc.
- String (12): `substring()`, `upper()`, `matches()`, etc.
- Math (13): `abs()`, `round()`, `sum()`, etc.
- Date/Time (6): `now()`, `today()`, etc.
- Type (8): `is()`, `as()`, `toInteger()`, etc.

### 4. Operators (20+ operators)
**Precedence levels**: 13 levels from highest to lowest
- Navigation: `.`, `[]`
- Arithmetic: `*`, `/`, `+`, `-`, `div`, `mod`
- Comparison: `=`, `!=`, `>`, `<`, `>=`, `<=`, `~`, `!~`
- Logical: `and`, `or`, `xor`, `implies`
- Collection: `|`, `&`, `in`, `contains`

### 5. Type System
**FHIR-aligned types**:
- System types: Boolean, String, Integer, Decimal, Date, DateTime, Time, Quantity
- FHIR types: Resource, Element, all FHIR resources and datatypes
- Type conversion and validation

### 6. Service API
**Simple interface** for end users:

```php
$service = new FHIRPathService();

// Simple evaluation
$result = $service->evaluate('Patient.name.given', $patient);

// Compiled expression (cached)
$expr = $service->compile('name.where(use="official").given');
$result = $expr->evaluate($patient);
```

## 📊 Implementation Statistics

| Category | Count | Time Estimate |
|----------|-------|--------------|
| **Classes** | ~80-100 | - |
| **Functions** | 50+ | 3-4 weeks |
| **Operators** | 20+ | 2-3 weeks |
| **Token Types** | 40+ | 1 week |
| **AST Node Types** | 10+ | 1 week |
| **Unit Tests** | 300+ | Ongoing |
| **Integration Tests** | 50+ | 2 weeks |
| **Total Time** | - | **13-19 weeks** |

## 🚀 Usage Examples

### Basic Path Navigation
```php
$service = new FHIRPathService();

// Get patient's given names
$result = $service->evaluate('Patient.name.given', $patient);

// Get active status
$result = $service->evaluate('Patient.active', $patient);

// Get phone numbers
$result = $service->evaluate('Patient.telecom.where(system="phone").value', $patient);
```

### Complex Queries
```php
// Official name's first given name
$expr = 'name.where(use = "official").given.first()';
$result = $service->evaluate($expr, $patient);

// All observations with high values
$expr = 'Observation.where(value > 100)';
$result = $service->evaluate($expr, $bundle);

// Check if patient has any phone
$expr = 'telecom.where(system = "phone").exists()';
$result = $service->evaluate($expr, $patient);
```

### With Caching
```php
// Compile once
$compiled = $service->compile('name.where(use="official").given');

// Evaluate many times (fast)
foreach ($patients as $patient) {
    $result = $compiled->evaluate($patient);
}
```

## 📈 Performance Targets

| Operation | Target | Notes |
|-----------|--------|-------|
| Simple path | < 1ms | `Patient.name` |
| Complex query | < 10ms | With functions and operators |
| Large resource | < 100ms | > 1MB resource |
| Compilation | < 5ms | Parse + optimize |
| Cache hit | < 0.1ms | Pre-compiled expression |

## 🧪 Testing Strategy

### Coverage Requirements
- **Unit Tests**: 90%+ coverage
- **Integration Tests**: All critical paths
- **Property Tests**: Mathematical and logical invariants
- **Compliance Tests**: Official FHIRPath test suite

### Test Categories
1. **Lexer Tests**: Token recognition
2. **Parser Tests**: AST construction
3. **Evaluator Tests**: Expression evaluation
4. **Function Tests**: Each function individually
5. **Operator Tests**: Each operator individually
6. **Type Tests**: Type system operations
7. **Integration Tests**: End-to-end scenarios
8. **Performance Tests**: Benchmarking

## 📚 Documentation Deliverables

### Technical Documentation
- ✅ Component Requirements (`docs/component-guides/fhir-path.md`)
- ✅ Architecture Guide (`docs/architecture-fhirpath.md`)
- ✅ Implementation Guide (`docs/FHIRPATH_IMPLEMENTATION_GUIDE.md`)
- ✅ Language Specification (`docs/FHIRPATH_LANGUAGE_SPEC.md`)

### User Documentation (To be created)
- [ ] README.md (Usage guide)
- [ ] API Reference (PHPDoc)
- [ ] Function Reference (All functions with examples)
- [ ] Tutorial (Step-by-step guide)
- [ ] Migration Guide (From other implementations)

## 🔄 Integration Points

### With Other Components

```
FHIRPath Component
├── Uses: CodeGeneration (for FHIR type definitions)
├── Uses: Serialization (for resource handling)
└── Used by: FHIRBundle (Symfony integration)
```

### Symfony Integration

```yaml
# config/packages/fhir.yaml
fhir:
    fhir_path:
        cache_enabled: true
        expression_cache_ttl: 3600
```

```php
// In controller
public function __construct(
    private readonly FHIRPathService $fhirPath
) {}
```

### Console Commands

```bash
# Evaluate expression
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Validate expression syntax
php bin/console fhir:path:validate "Patient.name.given"

# Compile expressions
php bin/console fhir:path:compile expressions.txt
```

## ⚠️ Implementation Challenges

### High Complexity Areas
1. **Parser**: Complex grammar with 13 precedence levels
2. **Collections**: Everything is a collection (empty, single, multiple)
3. **Three-Valued Logic**: true, false, and empty semantics
4. **Polymorphism**: Handling `value[x]` and similar patterns
5. **Performance**: Lazy evaluation and caching strategies

### Critical Decisions
1. **AST Structure**: How to represent expressions efficiently
2. **Collection Implementation**: Memory vs performance tradeoffs
3. **Type System**: How to integrate with FHIR types
4. **Caching Strategy**: What to cache and when to invalidate
5. **Error Reporting**: How to provide helpful error messages

## 📝 Development Phases

### Phase Overview

| Phase | Focus | Duration | Key Deliverables |
|-------|-------|----------|-----------------|
| 1 | Setup | 1 week | Project structure, composer.json |
| 2 | Lexer | 1 week | Token recognition |
| 3-4 | Parser | 2-3 weeks | AST construction |
| 5-6 | Evaluator | 2-3 weeks | Basic evaluation |
| 7-9 | Functions | 3-4 weeks | All 50+ functions |
| 10-11 | Operators | 2-3 weeks | All operators |
| 12-13 | Types | 2 weeks | Type system |
| 14 | Service | 1 week | Public API |
| 15 | Optimization | 1 week | Performance tuning |
| 16 | Documentation | 1 week | User docs |
| 17 | Integration | 1 week | Bundle integration |
| 18 | Review | 1 week | Final polish |

### Current Status: ✅ Requirements Complete

**Next Steps**:
1. Review requirements with team
2. Set up project structure (Phase 1)
3. Begin lexer implementation (Phase 2)

## 🎓 Learning Resources

### Required Reading
- [FHIRPath 2.0 Specification](http://hl7.org/fhirpath/N1/)
- [FHIRPath Grammar](http://hl7.org/fhirpath/N1/grammar.html)
- [FHIR R4B Specification](http://hl7.org/fhir/R4B/)

### Reference Implementations
- [fhirpath.js](https://github.com/HL7/fhirpath.js) (JavaScript)
- [FHIRPath.NET](https://github.com/FirelyTeam/fhirpath.net) (C#)

### Internal Documentation
- Architecture Guide: `docs/architecture.md`
- AGENTS.md: Development guidelines
- Component Guides: `docs/component-guides/`

## 🎯 Success Criteria

### Must Have
- [ ] Pass all FHIRPath 2.0 specification tests
- [ ] 90%+ code coverage
- [ ] PSR-12 compliant
- [ ] Zero PHPStan errors
- [ ] Performance targets met
- [ ] Complete documentation
- [ ] Symfony integration working

### Nice to Have
- [ ] Expression optimizer
- [ ] Query planner
- [ ] Visual expression builder
- [ ] Performance profiler
- [ ] IDE integration (language server)

## 📞 Getting Help

1. **Documentation**: Check all 4 requirement documents first
2. **Specification**: Consult FHIRPath 2.0 spec
3. **Reference**: Look at existing components (CodeGeneration, Serialization)
4. **Examples**: Review reference implementations
5. **Team**: Ask team members for guidance

## ✅ Quick Checklist

Before starting implementation:
- [ ] Read all 4 requirement documents
- [ ] Review FHIRPath 2.0 specification
- [ ] Understand existing component architecture
- [ ] Set up development environment
- [ ] Create feature branch
- [ ] Review implementation guide

During implementation:
- [ ] Follow coding standards (PSR-12, strict types)
- [ ] Write tests first (TDD approach)
- [ ] Document public APIs (PHPDoc)
- [ ] Run linter and PHPStan regularly
- [ ] Commit frequently with conventional commits
- [ ] Update progress regularly

Before completion:
- [ ] All tests pass (90%+ coverage)
- [ ] Code quality checks pass
- [ ] Documentation complete
- [ ] Performance benchmarks met
- [ ] Integration tests pass
- [ ] Final code review

---

**Total Time Estimate**: 13-19 weeks (3-5 months)

**Confidence Level**: High (well-defined requirements, clear architecture)

**Complexity Level**: Medium-High (parser/evaluator complexity, but well-documented)
