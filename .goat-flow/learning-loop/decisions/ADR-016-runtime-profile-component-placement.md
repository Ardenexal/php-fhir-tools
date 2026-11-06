# ADR-016: Runtime profile compiler in Metadata, registry and resolver in Validation

**Date:** 2026-11-07
**Status:** Accepted
**Deciders:** Alex (M01 acceptance), implementation (#170 M02)

## Context

Issue #170 validates resources against StructureDefinitions loaded at runtime. Three pieces are
needed: a compiler that turns a StructureDefinition into profile rules, a registry that holds
StructureDefinitions, and a resolver that walks a profile's `baseDefinition` chain.

The hard requirement is that a runtime profile gives the same findings as the generated profile
class for the same StructureDefinition. The generator already translated StructureDefinitions into
attributes, inside `fhir-code-generation`. `fhir-validation` must not depend on
`fhir-code-generation`, which pulls in package download, Nette PHP generation and the filesystem.

## Decision

- **The compiler lives in Metadata** (`Metadata\Profile\StructureDefinitionCompiler`). Both the
  generator and runtime validation need it, and Metadata is the package both already require
  (ADR-002: anything visible to more than one component belongs there). The generator renders the
  compiler's output; runtime validation instantiates it. One translation means the two cannot drift.
- **The registry and resolver live in Validation** (`Validation\Profile\`). Only runtime validation
  uses them. `PackageDirectoryStructureDefinitionLoader` reads an already extracted package
  directory; downloading packages stays in CodeGeneration's `PackageLoader`.
- **Every lookup is keyed by FHIR release** ('R4', 'R4B', 'R5') as well as URL, because core
  profiles such as `http://hl7.org/fhir/StructureDefinition/bp` exist in several releases.
- **Snapshots are required.** A StructureDefinition without one is reported as `no-snapshot` and
  never compiled (Alex, 2026-11-06).

## Consequences

- `fhir-code-generation` and `fhir-validation` both need the `fhir-metadata` release that ships
  `Metadata\Profile`; their cross-pins must be floored at that release.
- A rule kind added to the compiler changes generated classes and runtime validation together. A
  change that should affect only one of them has no home and needs a new decision.
- Validation cannot fetch a missing parent profile; the caller must register every package in the
  chain (typically the IG directory plus the core package, through
  `CompositeStructureDefinitionRegistry`).
- npm mirrors of FHIR packages ship no `.index.json`, so the package loader falls back to scanning
  the directory. Measured on `hl7.fhir.r4b.core` 4.3.0 (3,496 resources, warm file cache): about
  12 ms to index and resolve `bp` with an index, about 230 ms without, 10 MB peak either way.

## Alternatives considered

- **Compiler in Validation.** The generator would then need `fhir-validation`, or keep its own copy;
  a copy is exactly the drift the parity requirement forbids.
- **Registry in Metadata.** Nothing but runtime validation uses it, and Metadata would grow file
  system and package-format knowledge it does not need.
- **Reuse CodeGeneration's `PackageLoader`.** It depends on `BuilderContext`, HTTP download and
  Symfony Filesystem; using it would make Validation depend on CodeGeneration.
