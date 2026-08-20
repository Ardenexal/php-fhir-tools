# Glossary

Domain-specific terms used in this codebase.

| Term | Definition |
|---|---|
| **FHIR** | Fast Healthcare Interoperability Resources — HL7 standard for exchanging healthcare data. This project supports R4, R4B, and R5. |
| **R4 / R4B / R5** | FHIR release versions. R4 is widely deployed; R4B is a minor update; R5 is the current spec. Generated models live in `src/Component/Models/src/` under `R4/`, `R4B/`, and `R5/` subdirectories. |
| **Structure Definition** | The canonical FHIR metadata document that describes a resource or data type. CodeGeneration reads these JSON files to produce PHP classes. |
| **Resource** | A top-level FHIR entity (e.g. `Patient`, `Observation`). Marked with the `#[FhirResource]` PHP attribute. |
| **Primitive** | A scalar FHIR type (e.g. `string`, `boolean`, `dateTime`). Marked with `#[FHIRPrimitive]`. Can implement `Stringable` for transparent string coercion. |
| **ComplexType** | A FHIR data type composed of named properties (e.g. `HumanName`, `Address`). Marked with `#[FHIRComplexType]`. |
| **BackboneElement** | A complex structure that only exists inside a resource (no standalone use). Marked with `#[FHIRBackboneElement]`. |
| **Profile** | A FHIR constraint on a base resource or type (e.g. AU Base Patient). Marked with `#[FHIRProfile]`. |
| **Extension** | A FHIR mechanism for attaching extra data to any resource. Represented via `FHIRExtensionInterface` / `FHIRComplexExtensionInterface`. |
| **IG (Implementation Guide)** | A FHIR package that defines profiles, extensions, and value sets for a specific use case (e.g. AU Core, US Core). |
| **FHIRPath** | A path-based expression language for navigating FHIR resources, defined in the FHIR spec. Evaluated by `FHIRPathService`. |
| **FHIRPath Collection** | The fundamental FHIRPath data type — an ordered, non-unique list of items. All FHIRPath operations return Collections. |
| **SliceDiscriminator** | FHIR mechanism for identifying which slice of a sliced element applies. Represented by `#[FHIRSliceDiscriminator]` and `SliceDiscriminator`. |
| **Serialization context** | `FHIRSerializationContext` — immutable value object that configures a single serialize/deserialize call (format, validation mode, debug flags). |
| **Type registry** | `FHIRIGTypeRegistry` — maps FHIR resource/type names to PHP class names within a given IG, used by the deserializer to instantiate the right class. |
| **Metadata extraction** | `FHIRMetadataExtractor` reflects on PHP 8 attributes to build `PropertyMetadata` describing each property — used by normalizers at serialization time. |
| **Normalizer** | A class in `Serialization/src/Normalizer/` that encodes or decodes one category of FHIR type (Resource, ComplexType, BackboneElement, Primitive). |
| **PackageLoader** | `CodeGeneration/src/Package/PackageLoader` — downloads and caches FHIR npm-format packages (`.tgz`) from the FHIR package registry. |
| **Temporal value** | A FHIR date/time type. Handled by `FHIRTemporalValue` interface and `brick/date-time` under the hood. |
| **test-ai variants** | AI-optimised composer test commands (`composer test-ai*`) that emit compact one-line summaries instead of full PHPUnit output. Always prefer these over raw `composer test`. |
| **phpstan-ai variants** | AI-optimised composer PHPStan commands (`composer phpstan-ai*`) that emit compact one-line-per-error output. Always prefer these. |
