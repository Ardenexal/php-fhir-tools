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

## Proposed source layout (for the follow-up implementation)

```
docs/
├── SUMMARY.md                 # GitBook table of contents (drives the sidebar)
├── README.md                  # docs landing page (introduction)
├── getting-started/
│   └── installation.md
├── components/
│   ├── code-generation.md
│   ├── serialization.md
│   ├── fhir-path.md
│   └── fhir-bundle.md
├── models/                    # the one place FHIR R4/R4B/R5 differences live
│   ├── overview.md
│   └── versions.md            # R4 / R4B / R5 differences as sections or tabs
└── reference/
    └── console-commands.md
```

The existing per-component `README.md` files under `src/Component/*` remain the canonical
package-level docs; the GitBook `components/*` pages can summarise and link to them (or be generated
from them) to avoid duplication.

## Next steps (not done in this doc)

1. Confirm which release branches should be published as variants.
2. Restructure `docs/` to match the proposed layout and add `SUMMARY.md`.
3. Connect the GitBook space to this repo via Git Sync (`docs/` subdirectory, `main` branch).
4. Add a variant per supported release branch.
5. Add a docs badge/link to the root `README.md`.
