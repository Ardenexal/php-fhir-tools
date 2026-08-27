# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- [CodeGeneration] `fhir:generate` now generates CDA R2 logical models. `LogicalModelGenerator` handles `kind: logical` StructureDefinitions from `hl7.cda.uv.core` and `au.digitalhealth.cda.schema`, emitting 260 classes (179 clinical classes, 50 datatypes, 31 ValueSet enums) into the new standalone `ardenexal/cda-sd-models` package under `Ardenexal\FHIRTools\Component\CdaModels\{ClinicalClass,DataType,Enum}`; see `docs/code-generation/cda.md`
- [CodeGeneration] AU CDA classes generate as core specializations, not profiles. `au.digitalhealth.cda.schema` uses `derivation: specialization` to *add* XML elements rather than restrict them, so `au-ClinicalDocument` and its siblings are generated as real subclasses of their core counterparts instead of constrained views
- [CodeGeneration] CDA ValueSet enums are generated and bound to their coded properties, so a `code`-typed element carries a typed backed enum rather than a bare string
- [Metadata] `#[LogicalModel]` attribute (`url`, `name`, `fhirVersion`, `xmlNamespace`) marking a class as a FHIR logical model and carrying the XML namespace its elements belong to
- [Serialization] `FHIRLogicalModelXmlNormalizer` serializes and deserializes CDA logical models: the root element takes the class name (e.g. `<ClinicalDocument>`), `urn:hl7-org:v3` is declared once as the document default namespace, and no synthetic wrapper element is introduced
- [Serialization] `FHIRLogicalModelJsonNormalizer` guards the JSON path for XML-only CDA types, refusing with "CDA/HL7 V3 is an XML-only format. Use serializeToXml() instead" rather than emitting output no CDA consumer can read
- [Serialization] Transparent XML choice groups: `#[FhirProperty]` gained `choiceGroup` metadata and `ChoiceGroupItem` carries heterogeneous children, so wrapper-less choice groups round-trip in document order at any nesting depth

### Fixed
- [Serialization] Enum-typed properties now serialize and denormalize as XML attributes. A backed enum failed the `is_scalar()` guard in the `xmlAttr` emit branch and fell through to the generic normalizer chain ("no supporting normalizer found"), while the inverse branch cast every attribute to string and raised a `TypeError`. Enums now emit their backing code, V3 `SET<cs>` attributes (`AD.use`, `EN.use`, `ENXP.qualifier`, `TEL.use`) render space-delimited, and an unrecognised code throws `NotNormalizableValueException` naming both code and enum instead of being dropped
- [Serialization] CDA `sdtc` elements now round-trip in their own namespace. The emit side strips the `sdtc` prefix, so the decoded key never matched the prefixed property and the element was dropped with no signal. Elements whose local name collides across namespaces (`Patient.raceCode`, `Patient.ethnicGroupCode`, `CustodianOrganization.telecom`) also collapsed into one key on decode, so a conformant document was rejected on cardinality, and overwrote each other on emit
- [CodeGeneration] `fhir:generate` and `fhir:generate-ig` now run on Symfony 6.4, which `ardenexal/fhir-code-generation` has always declared (`symfony/console: ^6.4|^7.4`). Both commands were written in Symfony's invokable style (`__invoke()` with `#[Option]`/`#[Ask]` parameter attributes), which only exists from 7.3, so on 6.4 the options were never registered (`The "--package" option does not exist`) and invoking either command threw `You must override the execute() method`. They now use `configure()`/`execute()`, with an option definition transcribed from what the attributes produced on 7.x — same names, modes, defaults, descriptions and order — so 7.x behaviour is unchanged
- [Docs] Removed the stale "single-element repeating fields" XML limitation note from the serialization guide; single-value repeating fields (e.g. a `HumanName` with one `given`) already round-trip correctly through XML, and a regression test now guards this
- [Docs] Corrected the Questionnaire validation guide: `enableWhenExpression` (SDC + Kanta variants) and `regex` constraints are now documented as covered, the implementation-rules table lists the enforced constraint/value-domain/quantity `error` rules, and the conformance-coverage section reflects that all 78 eligible R4 cases are seeded and asserted (only SDC `answerExpression`/`calculatedExpression` and R5 `answerConstraint` remain uncovered)

### Changed
- [Core] `ardenexal/*` cross-package constraints raised from `^0.4` to `^0.5`, and every component's `dev-main` branch-alias moved to `0.5.x-dev`. Tag `0.4.0` of `ardenexal/fhir-metadata` carries no `$enumClass` on `FHIRValueSetBinding` while `main` both emits and requires it, so `^0.4` resolved a combination consumers cannot use (`Unknown named parameter $enumClass` from the validator's `AnnotationLoader`). Until `0.5.0` is tagged these constraints are satisfiable only by a `dev`-stability version: consumers tracking `dev-main` must require each `ardenexal/*` package explicitly at `dev-main`, or set `minimum-stability: dev` with `prefer-stable: true`

### Infrastructure
- [CI] `package-integrity` stamps sibling path repositories with `0.5.x-dev` so they match the declared branch aliases; the previous hardcoded `0.4.x-dev` overrode each sibling's own alias and left the `^0.5` cross-constraints unresolvable, failing every `Undeclared deps` job
- [CI] New `symfony-console-lower-bound` job invokes both generator commands against `symfony/console:6.4.*` through a standalone consumer install (`tests/Compat/symfony-console-6.4`), including a real R4B generation. Neither the monorepo root nor the demo app can host this: `brianium/paratest` requires console `^7.4.7` and `demo/composer.json` pins Symfony to `7.4.*`, which is why the existing `cross-version-test` "6.4.*" matrix leg has only ever installed console 7.x
- [CodeGeneration] Added `SymfonyConsoleCompatibilityTest`, which asserts on any console version that both commands override `execute()`, expose no `__invoke()`, reference none of the 7.3-only console attributes, and declare the documented `--package`/`--offline` surface

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
- [Core] **BREAKING:** The default FHIR version changed from R4B to R4 across all runtime and codegen entry points (`FHIRSerializationService::createDefault()`/`createWithIG()`, the `fhir.default_version` config node, `fhir:generate` default IG packages, and the `FHIRPrimitive`/`FHIRComplexType`/`FHIRBackboneElement` attribute defaults). Pass `FhirVersion::R4B` (or `default_version: 'R4B'`) explicitly to retain the previous behaviour
- [Models] **BREAKING:** Abstract resource base classes are no longer generated with a doubled `Resource` suffix; they now use an `Abstract` prefix: `ResourceResource` → `AbstractResource`, `DomainResourceResource` → `AbstractDomainResource`, and (R5 only) `CanonicalResourceResource` → `AbstractCanonicalResource`, `MetadataResourceResource` → `AbstractMetadataResource`. Concrete resource classes are unchanged (e.g. `PatientResource`)
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