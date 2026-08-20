# Code Map

```
/srv/php-fhir-tools/
├── src/
│   ├── Bundle/
│   │   └── FHIRBundle/
│   │       ├── src/                          = Symfony bundle (DI wiring, commands, cache warmer)
│   │       │   ├── DependencyInjection/      = FHIRExtension + Configuration
│   │       │   ├── Command/                  = fhirpath:evaluate, fhirpath:validate
│   │       │   ├── CacheWarmer/              = FHIRMetadataCacheWarmer
│   │       │   └── Compatibility/            = Symfony version shim
│   │       └── tests/                        = unit + integration tests
│   └── Component/
│       ├── Metadata/
│       │   └── src/
│       │       ├── Attribute/                = PHP 8 attributes: FhirResource, FhirProperty, FHIRPrimitive, FHIRComplexType, FHIRBackboneElement, FHIRProfile, FHIRExtensionDefinition, FHIRSliceDiscriminator
│       │       ├── Contract/                 = shared interfaces: FHIRTemporalValue, FHIRExtensionInterface, FHIRComplexExtensionInterface
│       │       └── Traits/                   = FHIRExtensionsTrait
│       ├── CodeGeneration/
│       │   └── src/
│       │       ├── Command/                  = fhir:generate, fhir:generate-ig console commands
│       │       ├── Generator/                = FHIRModelGenerator, FHIRValueSetGenerator, FHIRProfileGenerator, FHIRExtensionGenerator, FHIRConstrainedComplexTypeGenerator
│       │       ├── Package/                  = PackageLoader, DependencyResolver, SemanticVersionResolver, CacheIntegrityManager
│       │       ├── Context/                  = BuilderContext, GeneratedClassInfo
│       │       ├── Configuration/            = GenerationConfigurationInterface
│       │       └── Service/                  = CodeGenerationServiceInterface
│       ├── Models/
│       │   └── src/                          = GENERATED — do not hand-edit
│       │       ├── R4/                       = FHIR R4 models
│       │       ├── R4B/                      = FHIR R4B models
│       │       ├── R5/                       = FHIR R5 models
│       │       └── Primitive/                = Shared primitive types
│       ├── CdaModels/                        = separate package (ardenexal/fhir-cda-models, ADR-009); CDA/HL7-V3 logical models — scaffolded, generation pending (see plans/cda-logical-models)
│       ├── Serialization/
│       │   └── src/
│       │       ├── Context/                  = FHIRSerializationContext, FHIRSerializationContextFactory
│       │       ├── Metadata/                 = FHIRMetadataExtractor, FHIRMetadataCache, PropertyMetadata*
│       │       ├── Normalizer/               = per-type normalizers (Resource, ComplexType, BackboneElement, Primitive)
│       │       ├── Validator/                = FHIRValidator, FHIRSchemaValidator
│       │       └── Exception/                = serialization exceptions
│       ├── FHIRPath/
│       │   └── src/
│       │       ├── Parser/                   = FHIRPathLexer, FHIRPathParser, Token, TokenType
│       │       ├── Expression/               = AST nodes (BinaryOperatorNode, FunctionCallNode, etc.)
│       │       ├── Evaluator/                = FHIRPathEvaluator, EvaluationContext, Collection, ComparisonService
│       │       ├── Function/                 = ~100 FHIRPath functions (one class each) + FunctionRegistry
│       │       ├── Type/                     = FHIRPathDate, FHIRPathDateTime, FHIRPathTime, FHIRPathDecimal, TypeInfo
│       │       ├── Cache/                    = InMemoryExpressionCache
│       │       └── Service/                  = FHIRPathService, CompiledExpression
│       ├── Validation/
│       │   └── src/                          = FHIR resource validation (cardinality, value-set bindings, target profiles, FHIRPath invariants)
│       │       ├── (root)                    = FHIRQuestionnaireValidator + DerivedQuestionnaire validators, terminology client (CachingFHIRTerminologyClient), resolver interfaces, obligation context
│       │       └── Validator/                = per-rule validators: ValueSetBinding, TargetProfile, SliceConstraint, PathInvariant, FixedValue, PatternValue, QuantityRange, TemporalRange, ProfileConstraint
│       └── Sdc/
│           └── src/                          = SDC operations (version-generic R4/R4B/R5)
│               ├── FHIRQuestionnaireResponseExtractService = QuestionnaireResponse/$extract entry point (observation/definition/template → one transaction Bundle)
│               ├── DefinitionPathWriter       = writes answers into typed model props via #[FhirProperty] reflection
│               ├── TemplateExtractor          = clones #contained templates and FHIRPath-fills them
│               └── ExtractModelFactory · ExtractContext · ExtractResult · ExtractServiceInterface = per-version envelope construction + I/O DTOs
├── demo/
│   └── bin/console                           = Symfony console entry point for dev code-gen runs
├── resources/
│   └── definitions/                          = FHIR package zip cache (downloaded by PackageLoader)
├── scripts/                                  = ai-test-runner.php, ai-phpstan-runner.php, and utility scripts
├── bench/                                    = PHPBench benchmarks (Serialization, FHIRPath)
├── tests/                                    = top-level integration tests (FlexRecipeTest, etc.)
├── mate/                                     = AI Mate MCP configuration (extensions.php, config.php, AGENT_INSTRUCTIONS.md)
├── .goat-flow/                               = GOAT Flow workflow state (architecture, decisions, lessons, etc.)
├── vendor/                                   = Composer dependencies — never edit
├── composer.json                             = package manifest + all composer scripts
├── phpstan.neon                              = PHPStan level 8 config
└── phpunit.xml                               = PHPUnit test suite configuration
```

## Hot paths

| Task | Entry point |
|---|---|
| Generate FHIR models | `src/Component/CodeGeneration/src/Command/FHIRModelGeneratorCommand.php` |
| Serialize/deserialize FHIR JSON/XML | `src/Component/Serialization/src/FHIRSerializationService.php` |
| Evaluate FHIRPath expressions | `src/Component/FHIRPath/src/Service/FHIRPathService.php` |
| Extract resources from a QuestionnaireResponse (SDC `$extract`) | `src/Component/Sdc/src/FHIRQuestionnaireResponseExtractService.php` |
| Validate a FHIR resource (bindings, profiles, invariants) | `src/Component/Validation/src/Validator/` |
| Symfony DI wiring | `src/Bundle/FHIRBundle/src/DependencyInjection/FHIRExtension.php` |
| PHP 8 attributes on models | `src/Component/Metadata/src/Attribute/` |

## Never-edit paths

- `src/Component/Models/src/` — generated output; regenerate with `composer generate-models-all`
- `vendor/` — Composer-managed
