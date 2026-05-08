# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/Ardenexal/FHIR-Structure-Definition-php-generator/compare/v0.3.1...HEAD
[0.3.1]: https://github.com/Ardenexal/FHIR-Structure-Definition-php-generator/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/Ardenexal/FHIR-Structure-Definition-php-generator/compare/0.2...v0.3.0
[0.2]: https://github.com/Ardenexal/FHIR-Structure-Definition-php-generator/releases/tag/0.2