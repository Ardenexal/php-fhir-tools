# Architecture

## System Overview

PHP FHIR Tools is a PHP 8.3+ monorepo library for working with FHIR (Fast Healthcare Interoperability Resources). It is divided into eight components and one Symfony bundle, each with its own `src/` and `tests/` subtree. Components are kept separate so that consumers can depend only on what they need: **Metadata** holds shared PHP 8 attributes and interfaces (any interface or attribute visible to more than one component lives here); **CodeGeneration** generates model classes from FHIR Structure Definitions; **Models** holds the generated R4/R4B/R5 output; **CdaModels** holds the separately-packaged CDA (HL7 V3) logical models (`ardenexal/fhir-cda-models`, split out per ADR-009); **Serialization** handles FHIR JSON/XML encoding/decoding against generated models; **FHIRPath** evaluates FHIRPath 2.0 expressions; **Validation** validates deserialized models against cardinality, value-set bindings, target profiles, and FHIRPath invariants; **Sdc** implements the Structured Data Capture operations (`QuestionnaireResponse/$extract`, and `Questionnaire/$populate` via the `sdc-populate` plan); **FHIRBundle** wires everything into a Symfony application via dependency injection.

## Request / Command Flow

The primary entry points are Symfony Console commands:

1. `fhir:generate` (`FHIRModelGeneratorCommand`) — loads a FHIR package via `PackageLoader` → resolves dependencies via `DependencyResolver` → runs generators (`FHIRModelGenerator`, `FHIRValueSetGenerator`, `FHIRProfileGenerator`, etc.) → writes PHP files to `src/Component/Models/src/`.
2. `fhir:generate-ig` (`FHIRIGGeneratorCommand`) — same pipeline but scoped to Implementation Guides.
3. In a Symfony app: `FHIRBundle` registers `FHIRSerializationService` and `FHIRPathService` in the DI container; the bundle also exposes `fhirpath:evaluate` and `fhirpath:validate` commands.

## Data Flow

```
resources/definitions/           ← FHIR package zips (downloaded/cached)
          ↓
CodeGeneration component          ← reads StructureDefinition JSON
          ↓
src/Component/Models/src/         ← generated PHP classes (NEVER hand-edit)
          ↓
Serialization component           ← reflects PHP 8 attributes to build metadata
          ↓                         (FHIRMetadataExtractor → FHIRMetadataCache)
JSON / XML payloads               ← FHIRSerializationService encode/decode
```

FHIRPath evaluation is orthogonal: `FHIRPathService` accepts a compiled expression and a PHP FHIR model object, walks object properties via reflection, and returns a `Collection`.

## Operation Generation and Parameters Mapping

FHIR operations exchange arguments as a `Parameters` resource. Typed classes for them are generated
alongside the models, and a mapper converts between the two — so operations reuse the existing
serializer rather than a parallel encoding path.

```
OperationDefinition JSON (core packages)
          ↓  PackageLoader admits resourceType OperationDefinition into BuilderContext
FHIROperationGenerator                    ← runs under fhir:generate only
          │  BuilderContextTypeIndex      → classifies each parameter's type
          │  AllowedTypeReader            → allowed-type variants for Element/* parameters
          │  VariantOrderer               → orders variants subclass-before-superclass
          │  OutputShapeClassifier        → Parameters / BareResource / NamedBareResource / NoOutput
          │  OperationClassNamer          → collision-free PHP identifiers, or throws
          ↓
src/Component/Models/src/{v}/Operation/{Stem}/    ← Input, Output, Operation holder, part classes
          ↓  #[FhirOperation] · #[FhirOperationPayload] · #[FhirOperationParameter]
OperationParameterMapper                  ← Serialization component, reflection-driven
          ↓  toParameters() / fromParameters() / fromResponse() / toResponse()
ParametersResource → FHIRSerializationService → JSON / XML
```

Four things about this flow are load-bearing:

- **`AllowedTypeReader` unions two sources, and the extension is the live one.** R5 added a
  first-class `parameter.allowedType` element, but no shipped OperationDefinition in any version
  populates it — the `operationdefinition-allowed-type` extension is the only source with data today.
  Reading the element alone, or branching on version, yields zero variants. See the footgun entry
  `operation-allowed-type-sources.md`.
- **The output shape is a generation-time decision, not a runtime guess.** Only ~14 of 47 operations
  per version answer with a `Parameters`; the rest answer with a bare resource or nothing. The shape
  is carried on the holder so `fromResponse()` never has to infer it from the body.
- **The mapper resolves classes through `FHIRTypeResolver`, never by interpolating FQCNs.** A
  hand-built `Models\{version}\…` name resolves only base-spec classes and silently ignores
  registered profiles.
- **Operation payload classes carry no invariants and are never validated directly.** Conformance is
  judged on the `Parameters` the mapper emits, which does carry them — including on
  `Parameters.parameter`, reached via the `Valid` cascade the model generator emits.

`fhir:generate-ig` does not emit operation classes; it builds no type index. IG-sourced
OperationDefinitions are therefore unsupported, tracked in `plans/operation-codegen/backlog.md`.

User-facing docs: `docs/code-generation/operations.md` and `docs/serialization/operations.md`.

## SDC Operations (`Sdc` component)

Structured Data Capture is a service-layer API (not a console command), version-generic across R4/R4B/R5.
`QuestionnaireResponse/$extract` (`FHIRQuestionnaireResponseExtractService::extract(object $qr, ExtractContext $ctx)`)
turns a completed `QuestionnaireResponse` into FHIR resources per the SDC extraction spec:

```
QuestionnaireResponse (+ source Questionnaire in ExtractContext)
          ↓  read SDC extension directives by URL (SafeExtensionReader)
  ┌───────┴────────┬─────────────────────┐
  observationExtract  definitionExtract     templateExtract
  → Observation/answer  → DefinitionPathWriter  → TemplateExtractor
     (R4 only)            writes into typed model    clones a #contained
                          props via #[FhirProperty]   template, FHIRPath-fills
                          reflection (R4/R4B/R5)       it (R4/R4B/R5)
  └───────┬────────┴─────────────────────┘
          ↓  merge (ExtractModelFactory builds per-version envelopes)
  transaction Bundle (POST create / PUT update; never DELETE)
  + companion OperationOutcome (info/warning issues)
  + optional Provenance entry (ExtractContext.emitProvenance)
```

The three methods share one merged transaction Bundle. `ExtractModelFactory` is the single place that
resolves per-version FQCNs (`Models\{R4,R4B,R5}\…`) and constructs the Bundle/OperationOutcome/Provenance
envelopes, keeping the rest of the service statically typed. Correctness is anchored to the
`sdc-foundation` oracle harness (structural comparison against reference-impl-seeded baselines). Emitted
resources that no engine can oracle (opt-in Provenance, the cross-method merge) are validated via the
`Validation` component instead — see the footgun `generated-model-nullable-cardinality`.

`Questionnaire/$populate` (`FHIRQuestionnairePopulateService::populate(object|string $questionnaire, PopulateContext $context)`)
runs the inverse direction — it pre-fills a `QuestionnaireResponse` from a `Questionnaire` plus caller-supplied
context, per the SDC population spec:

```
Questionnaire (object, or canonical string resolved via Validation's FHIRQuestionnaireResolverInterface)
          ↓  read SDC extension directives by URL (SafeExtensionReader)
  ┌───────┴────────┬──────────────────────┬────────────────────┐
  launchContext      variable / itemPop-      initialExpression     observationLinkPeriod
  → FHIRPath external  ulationContext         → answer.value[x]      → most-recent Observation
    constants (%name)  → ordered var chains,    (type-coerced per      within window from the
                       repeating groups         the SDC rules)         supplied data Bundle
  └───────┬────────┴──────────────────────┴────────────────────┘
          ↓  assemble (PopulateModelFactory builds per-version envelopes)
  QuestionnaireResponse (in-progress)
  + companion OperationOutcome (info/warning issues — never throws)
```

Population is **offline-first**: launch-context resources and the `data` Bundle arrive through the
`PopulationDataProviderInterface` seam (`BundlePopulationDataProvider` is the shipped implementation) —
no live `x-fhir-query`/`dataEndpoint` fetching. It is **FHIRPath-only** (CQL deferred) and populates items
regardless of `enableWhen` (a display-time concern, not a population one). `populate()` never throws — every
diagnostic (unresolved canonical, malformed expression, missing context, coercion mismatch, empty result,
silent-drop) is emitted as an issue on the returned `PopulateResult`'s OperationOutcome. Correctness is
anchored to the same `sdc-foundation` oracle harness. Boundaries and exclusions are recorded in
ADR-011 (`sdc-populate-boundaries`); access-control/PHI filtering is explicitly a caller responsibility.

## Auth / Trust Boundaries

This is a library with no auth layer. Trust boundary = caller's application. Validation is opt-in via `FHIRSerializationContext::withValidationMode()`.

## Deployment / Operations

- Distributed as a Composer package (`ardenexal/fhir-tools`).
- CI runs lint (`pint`), static analysis (`phpstan` level 8), and full test suite.
- Use `composer quality:all` locally before pushing.
- `demo/` is a throwaway Symfony app used only for running the code-generation commands during development.
