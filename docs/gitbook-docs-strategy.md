# GitBook Documentation Strategy

## Overview

This document records the recommended strategy for publishing the project's documentation on
[GitBook](https://www.gitbook.com/), including where the source lives and how it is versioned.

It is a decision/strategy doc only — it does not change the current `docs/` layout. Implementation
(restructuring `docs/`, adding a `SUMMARY.md`, and connecting Git Sync) is a follow-up.

## Decisions at a glance

| Question | Decision |
|----------|----------|
| Where do the docs live? | **In this repository**, under `docs/`, synced to GitBook via Git Sync. |
| How is the table of contents defined? | A `SUMMARY.md` committed alongside the docs. |
| How are versions handled? | **Library release versions** become GitBook *variants*, each synced from a git branch. |
| How are FHIR R4 / R4B / R5 handled? | As **content sections within one doc tree**, *not* as separate doc copies. |

## Why the docs stay in this repo

The project is a library monorepo (`fhir-bundle`, `fhir-code-generation`, `fhir-serialization`,
`fhir-path`, `fhir-models`) and already follows a docs-as-code approach: a `docs/` folder plus
per-component `README.md` files. Keeping documentation in this repository is preferred because:

- **Single source of truth / atomic PRs** — a code change and its documentation update land in the
  same pull request and the same review, so the two cannot drift apart.
- **GitBook Git Sync is built for this** — a GitBook space connects to a GitHub repository, branch,
  and subdirectory (e.g. `docs/`) and two-way syncs Markdown plus a `SUMMARY.md`. A monorepo
  subfolder is a first-class, supported setup.
- **Versioning maps cleanly onto git** — GitBook variants sync per branch, and this project already
  maintains release branches (e.g. `release/0.4.0`), so no extra versioning machinery is needed.

A separate documentation repository would only be worthwhile if the docs had a distinct contributor
base or permission model, or if we were aggregating documentation for several unrelated products.
Neither applies here, and a split repo would reintroduce the code/doc drift that a monorepo avoids.

## Versioning: two independent axes

"Different versions" can mean two unrelated things in this project. They are handled differently.

### 1. Library release versions → GitBook variants synced from branches

Each maintained release line is a git branch; each branch syncs to a GitBook *variant*, giving
readers a version dropdown.

- `main` → the current/unreleased docs (the "latest" variant).
- `release/0.4.0` (and future `release/*`) → a pinned variant per published release line.

Workflow:

1. In GitBook, create one space with Git Sync pointed at this repo's `docs/` directory.
2. Add a variant per release branch you want published.
3. When a release branch is cut, its docs freeze automatically — later edits on `main` do not
   affect the published older variant.

Only publish variants for release lines you intend to support; each variant is ongoing maintenance.

### 2. FHIR R4 / R4B / R5 → content sections, not separate copies

FHIR spec versions are a property of the *content*, not a release of this library. The large
majority of the docs (serialization, FHIRPath, the Symfony bundle, code generation) behave
identically across R4, R4B, and R5. Only the **model reference** genuinely differs per FHIR version.

Therefore, model R4 / R4B / R5 as **sections (or tabbed pages) within a single doc tree**, scoped to
the pages where the difference actually matters. Treating each FHIR version as a full GitBook variant
would roughly triple maintenance for content that is ~90% duplicated, and would multiply against the
library-version axis (a version matrix) for no reader benefit.

## Information architecture

The structure below is the recommended GitBook table of contents. It applies a few documentation
best practices:

- **One concern per page.** Large multi-topic pages are split. The current
  `Validation/README.md`, for example, covers five separable domains in one file; each becomes its
  own page (and Questionnaire validation, which uses a *different* validator, is its own page).
- **Organise by reader journey, not by repository folder.** Readers arrive wanting to *do* something
  (install, serialize, validate), so the top level is task-oriented, not a mirror of `src/`.
- **Progressive disclosure.** Each component opens with an overview + quick start, then drills into
  detail and reference. A reader can stop as soon as they have what they need.
- **Separate user-facing docs from internal/contributor docs.** Requirement specs, refactor plans,
  and gap-analysis trackers are valuable but are *not* product documentation — they live in a
  Contributor section (or stay in the repo, unpublished) so they don't dilute the user-facing docs.
- **Reference is generated/derived where possible.** The per-component `src/Component/*/README.md`
  files stay canonical at the package level; GitBook pages summarise and link to them rather than
  duplicating, to prevent drift.

### Top-level sections

1. **Introduction** — what the library is, the monorepo/package map, when to use which package.
2. **Getting Started** — installation (standalone vs. Symfony), a first end-to-end example.
3. **Code Generation** — generating base models, generating Implementation Guides, generated output.
4. **Models** — using generated models; the one place FHIR R4/R4B/R5 differences are documented.
5. **Serialization** — JSON, XML, context/options, IG-aware serialization.
6. **Validation** — split into focused pages (see below); Questionnaire validation is its own page.
7. **FHIRPath** — overview, expressions/operators, a per-category function reference, advanced.
8. **Symfony Bundle** — bundle config, services/DI, console commands, the Flex recipe.
9. **Reference** — console command reference, configuration reference.
10. **Contributing** — dev setup, testing, commit standards, and internal design/roadmap docs.

### Proposed `SUMMARY.md` (GitBook table of contents)

```markdown
# Table of contents

* [Introduction](README.md)

## Getting Started
* [Installation](getting-started/installation.md)
* [Quick Start](getting-started/quick-start.md)
* [Choosing the Right Package](getting-started/packages.md)

## Code Generation
* [Overview](code-generation/overview.md)
* [Generating Base FHIR Models](code-generation/base-models.md)
* [Generating Implementation Guides](code-generation/implementation-guides.md)
* [Generated Output Structure](code-generation/output-structure.md)

## Models
* [Using Generated Models](models/usage.md)
* [Namespace Organization](models/namespaces.md)
* [FHIR Versions: R4 / R4B / R5](models/fhir-versions.md)   # the only version-specific page

## Serialization
* [Overview](serialization/overview.md)
* [JSON Serialization](serialization/json.md)
* [XML Serialization](serialization/xml.md)
* [Serialization Context & Options](serialization/context.md)
* [IG-Aware Serialization](serialization/ig-aware.md)
* [Round-Trip Testing](serialization/round-trip.md)

## Validation
* [Overview & Architecture](validation/overview.md)
* [Structural & Profile Validation](validation/structural.md)   # cardinality, slices, fixed/pattern
* [FHIRPath Invariant Validation](validation/invariants.md)
* [Terminology & Binding Validation](validation/terminology.md)
* [Reference & Target Profile Validation](validation/references.md)
* [Quantity & Temporal Range Validation](validation/ranges.md)
* [Extensions, Modifiers & Obligations](validation/extensions.md)
* [Questionnaire Validation](validation/questionnaire.md)       # distinct validator
* [The $validate Operation (OperationOutcome)](validation/operation-outcome.md)
* [Configuration](validation/configuration.md)                  # terminology clients, resolvers
* [Validation Reports & Violation Codes](validation/reports.md)

## FHIRPath
* [Overview & Quick Start](fhirpath/overview.md)
* [Expressions & Operators](fhirpath/expressions.md)
* [Function Reference](fhirpath/functions/README.md)
  * [Existence & Collection](fhirpath/functions/existence.md)
  * [Filtering & Subsetting](fhirpath/functions/filtering.md)
  * [String](fhirpath/functions/string.md)
  * [Math](fhirpath/functions/math.md)
  * [Date & Time](fhirpath/functions/datetime.md)
  * [Type Conversion & Checking](fhirpath/functions/types.md)
  * [Tree Navigation & Utility](fhirpath/functions/navigation.md)
  * [FHIR-Specific](fhirpath/functions/fhir.md)
* [Compilation, Caching & Performance](fhirpath/performance.md)
* [Implementation Status & Known Issues](fhirpath/status.md)

## Symfony Bundle
* [Installation & Configuration](bundle/configuration.md)
* [Services & Dependency Injection](bundle/services.md)
* [Console Commands](bundle/console-commands.md)
* [Flex Recipe](bundle/flex-recipe.md)

## Reference
* [Console Command Reference](reference/commands.md)
* [Configuration Reference](reference/configuration.md)

## Contributing
* [Development Setup](contributing/setup.md)
* [Testing](contributing/testing.md)
* [Commit Standards](contributing/commit-standards.md)
* [Design Notes & Roadmap](contributing/design-notes.md)   # internal specs/refactor plans/gap analysis
```

### Source-to-page mapping (where today's content goes)

| Existing source | Destination page(s) |
|-----------------|---------------------|
| `src/Component/CodeGeneration/README.md` | Code Generation → Base Models + Implementation Guides + Output Structure |
| `src/Component/Models/README.md` | Models → Usage + Namespaces + FHIR Versions |
| `src/Component/Serialization/README.md` + `docs/component-guides/serialization.md` | Serialization → all pages (merge the two overlapping sources) |
| `src/Component/Validation/README.md` | Validation → split across the 11 pages above |
| `src/Component/FHIRPath/README.md` | FHIRPath → Overview, Expressions, Function Reference, Performance, Status |
| `src/Bundle/FHIRBundle/README.md` + `docs/component-guides/fhir-bundle.md` | Symfony Bundle → Configuration + Services + Console Commands |
| `recipe/README.md`, `recipe/fhir-bundle/1.0/*.md` | Symfony Bundle → Flex Recipe (consolidate the four recipe docs) |
| `tests/README.md` | Contributing → Testing |
| `docs/coding-standards/git-commit.md` | Contributing → Commit Standards |
| `docs/component-guides/fhir-path.md` (requirements spec) | Contributing → Design Notes & Roadmap (internal) |
| `docs/component-guides/fhirpath-gap-analysis-tasks.md` | Contributing → Design Notes & Roadmap (internal) |
| `docs/normalizer-refactor-plan.md` | Contributing → Design Notes & Roadmap (internal) |

### Notable splits (the "break it up" work)

- **Validation** (1 file → 11 pages). The README mixes general structural validation, terminology,
  references, ranges, extensions/obligations, questionnaire validation, the `$validate` operation,
  configuration, and report structure. Questionnaire validation uses a dedicated
  `FHIRQuestionnaireValidator` (plus derived-questionnaire variants) and is genuinely a separate
  workflow, so it gets its own page as you suggested.
- **FHIRPath functions** (1 long table → category pages). ~100 functions across 13 categories is a
  classic reference-overload page; grouping by category makes it navigable and searchable.
- **Serialization** (2 overlapping sources → one set of format-oriented pages). The component README
  and the component guide largely duplicate each other; merge, then split by format (JSON/XML) plus
  context and IG-aware serialization.
- **Code Generation** (1 file → base vs. IG). Base-model generation and IG generation are distinct
  workflows with distinct generator classes; separating them avoids one page that serves two jobs.
- **Internal docs pulled out of the user path.** The FHIRPath requirements spec, the gap-analysis
  tracker, and the normalizer refactor plan are development artifacts, not product docs — grouped
  under Contributing → Design Notes so they remain available without cluttering user navigation.

## Next steps (not done in this doc)

1. Confirm which release branches should be published as variants.
2. Restructure `docs/` to match the information architecture above and add `SUMMARY.md`.
3. Decide whether GitBook pages embed/transclude the `src/Component/*/README.md` content or summarise
   and link, to keep a single source of truth.
4. Connect the GitBook space to this repo via Git Sync (`docs/` subdirectory, `main` branch).
5. Add a variant per supported release branch.
6. Add a docs badge/link to the root `README.md`.
