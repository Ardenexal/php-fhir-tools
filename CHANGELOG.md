# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.4.0] - 2026-06-12

### Added
- [Validation] New `ardenexal/fhir-validation` component: `FHIRValidationService` validates resources against profiles and reports results as a FHIR `OperationOutcome`
- [Validation] Constraint validators for profile constraints, fixed values, pattern values, slices, value-set bindings (required/extensible/preferred + `maxValueSet`), `mustSupport`, obligations, target profiles, and FHIRPath invariants
- [Validation] Terminology validation: pluggable terminology client (`NullFHIRTerminologyClient` and HTTP client with `$validate-code` POST support), strict mode, and PSR-6 caching of terminology results with configurable TTL
- [Validation] Questionnaire and QuestionnaireResponse validation: `FHIRQuestionnaireValidator` and `FHIRDerivedQuestionnaireValidator` covering min/max occurs, `enableWhen` expressions, status/`effectivePeriod`, and preferred terminology server resolution; seeded with the brianpos and ardenexal R4/R4B/R5 conformance corpora
- [Validation] `FHIRTemporalRange` and `FHIRQuantityRange` constraints and validators; quantity bounds are compared only when the instance and bound share the same unit (system+code), with cross-unit bounds surfaced as warnings (no UCUM conversion)
- [Validation] Extension context validation via `FHIRExtensionContext` and `FHIRContextInvariant` attributes, including recursive/nested context evaluation through FHIRPath
- [Metadata] `ardenexal/fhir-metadata` is now standalone-installable; `FHIRIGTypeRegistry` and its factory moved here from Serialization
- [Serialization] All primitive types now implement `Stringable`
- [Bundle] `fhir.validation`, terminology cache, and message-override configuration keys

### Fixed
- [Serialization] FHIR primitive extension arrays on array-typed fields now serialize and deserialize correctly in complex and backbone normalizers (spec-compliant round-trip)
- [Serialization] XML deserialization of primitive-with-extension elements
- [Serialization] Consistent extension-URL resolution across normalizers
- [Validation] Temporal-range (`minValue`/`maxValue`) constraints are now enforced on real model objects instead of silently skipping `Stringable` primitive wrappers
- [FHIRPath] Quantity add/subtract no longer fabricates wrong-unit results; quantity divide-by-zero now returns an empty collection instead of throwing `DivisionByZeroError`
- [Validation] Guard uninitialized typed properties in `FHIRValidationService` to prevent crashes on constructor-bypassed (deserialized) objects
- [Metadata] `FHIRExtensionsTrait` lookup helpers no longer error on uninitialized `extension` properties

### Changed
- [Serialization] Normalizers split into `Normalizer\Json\*` and `Normalizer\Xml\*` namespaces
- [Validation] Conformance semantics aligned with the dotnet/brianpos reference for partial-date bounds, quantity bounds, and draft/in-progress leniency (ADR-008)
- [Docs] Rewrote the Serialization and FHIRBundle component guides to match the real public API (`FHIRSerializationService::createDefault()`, `serializeToJson`/`deserializeFromJson`, immutable `FHIRSerializationContext`)

### Infrastructure
- [Metadata] PHP floor raised to `>=8.3`; `symfony/finder` dependency declared
- [CI] ParaTest-based parallel test execution; consolidated unit and integration suites into a single parallelized matrix; PHPStan analysis of generated models added to CI
- [CI] Codecov configuration and phpbench baseline detection fixes

## [0.3.1] - 2026-05-07

### Fixed

- Fix failing serialization unit tests
- Update component version constraints to `^0.3`

## [0.3.0] - 2026-05-06

### Added
- [IG Generation] `fhir:generate-ig` command for generating typed PHP classes from FHIR Implementation Guide StructureDefinitions
- [IG Generation] `FHIRExtensionGenerator` for simple (single value type) and complex (sub-extension slices) typed extension classes
- [IG Generation] `FHIRProfileGenerator` for profile subclasses with multi-level inheritance support
- [IG Generation] `FHIRExtensionDefinition` and `FHIRProfile` metadata attributes for runtime introspection
- [IG Generation] Transitive IG dependency package auto-loading into `BuilderContext` for cross-package type resolution
- [IG Generation] FHIR R4B/R5 extension classes and FHIR R5 enums for various value sets
- [IG Generation] Slice discriminator support
- [Serialization] `FHIRIGTypeRegistry` for URL-to-class mapping of typed IG extension and profile subclasses
- [Serialization] `FHIRIGRegistryCompilerPass` to scan the IG output directory and wire up the registry at compile time
- [Serialization] `FHIRComplexExtensionInterface` with `fromSubExtensions()` factory for complex extension deserialization
- [Serialization] `PropertyMetadataProvider` now walks the full class hierarchy so typed extension subclasses inherit `FhirProperty` metadata from parent classes
- [Bundle] `fhir.ig.*` configuration keys (`namespace`, `output_directory`, `offline`, `packages`) in `config/packages/fhir.yaml`
- [Bundle] FHIR metadata cache warmer with configurable `enable_cache_warmer` option
- [Metadata] `FHIRExtensionInterface` providing a common `getExtensionUrl()` typing point across R4/R4B/R5
- [Metadata] `FHIRExtensionsTrait` with lookup helpers: `findExtension`, `findExtensions`, `hasExtension`, `findExtensionByUrl`, `findExtensionsByUrl`
- [FHIRPath] Benchmarks for FHIRPath evaluation and parsing
- [Infrastructure] Deployment workflow and documentation for the demo app

### Changed
- [Serialization] Null checks and type casting added for primitives
- [Serialization] Extensions and normalizers updated for versioning and deprecation handling
- [Docs] `fhir:generate-ig` command, IG bundle configuration, and component guides documented in README

### Fixed
- [IG Generation] Base constraint profiles (vitalsigns, bp, bodyheight, etc.) now generated so IG profiles that extend them resolve correctly
- [IG Generation] Slice parameter names conflicting with parent `Extension` properties now suffixed with `Slice` to avoid PHP property invariance compile errors
- [Serialization] Base extensions registered in `FHIRIGTypeRegistry` so deserializer uses typed subclasses instead of falling back to `Extension`
- [Serialization] Complex extension deserialization now uses `fromSubExtensions()` to correctly populate typed promoted properties
- [Serialization] PHPStan and PHPUnit notice issues in serialization layer
- [Infrastructure] Dockerfile updated with zip extension for Composer dist downloads
- [Infrastructure] Demo `composer.lock` constrained to PHP 8.3 compatible packages
- [FHIRPath] PHPDoc type hints improved in `FHIRPathEvaluator`

[Unreleased]: https://github.com/Ardenexal/php-fhir-tools/compare/v0.4.0...HEAD
[0.4.0]: https://github.com/Ardenexal/php-fhir-tools/compare/v0.3.1...v0.4.0
[0.3.1]: https://github.com/Ardenexal/php-fhir-tools/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/Ardenexal/php-fhir-tools/compare/0.2...v0.3.0
[0.2]: https://github.com/Ardenexal/php-fhir-tools/releases/tag/0.2