---
date: 2026-05-20
status: accepted
---

# ADR-001: R4 Model Generation Baseline — 30 s Wall-Clock

**Status:** accepted
**Date:** 2026-05-20

## Context

M01 of the FHIR StructureDefinition Validation feature (`fhir-struct-validation`) established a baseline
generation time before any constraint-emission code is added to `FHIRModelGenerator`. M03's kill criterion
requires generation regression to be detected if the new code adds more than ~10% overhead.

## Decision

Baseline R4 generation time is **30 seconds wall-clock** (2026-05-20), measured by running:

```bash
time php demo/bin/console fhir:generate --package=hl7.fhir.r4.core --package=hl7.fhir.uv.extensions.r4 -vvv
```

on this machine (WSL2, Linux 6.6.87.2-microsoft-standard-WSL2).

## Kill threshold for M03

If R4 generation with constraints enabled exceeds **33 seconds wall-clock** (≈10% above baseline),
the constraint-emission approach must be profiled and optimised before merging.

## Consequences

- M03's kill criterion uses this baseline: if R4 generation exceeds 33 s (≈10% overhead), the constraint-emission approach must be profiled before merging.
- Models component (`src/Component/Models/src/`) is gitignored generated output.
- Baseline was captured with an empty `src/Component/Models/src/` directory (cold generation).
- Machine-specific: re-establish baseline if the measurement machine changes significantly.
