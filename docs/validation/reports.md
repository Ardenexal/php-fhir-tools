---
description: The structure of validation reports, violations, and violation codes.
icon: file-lines
---

# Validation Reports & Violation Codes

A validation run returns a structured report:

* `FHIRValidationReport` — the overall result
* `FHIRValidationViolation` — an individual finding
* `FHIRViolationCode` — the violation code enumeration

<!-- TODO: migrate report/violation/code details from src/Component/Validation/README.md -->

To turn a report into a FHIR resource, see [The $validate Operation](operation-outcome.md).

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (Validation Report, FHIRValidationViolation,
     FHIRViolationCode) -->
