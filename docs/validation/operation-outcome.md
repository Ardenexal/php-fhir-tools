---
description: Produce an OperationOutcome for the FHIR $validate operation.
icon: file-circle-check
---

# The $validate Operation

`validateForOperation()` maps validation results to a FHIR `OperationOutcome`, suitable for
implementing the `$validate` operation on a server.

## Violation → OperationOutcome mapping

<!-- TODO: migrate the OperationOutcome mapping table from src/Component/Validation/README.md -->

See [Validation Reports & Violation Codes](reports.md) for the underlying report structure.

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (OperationOutcome Mapping) -->
