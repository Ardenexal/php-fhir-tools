---
description: Validate FHIR resources against the specification and Implementation Guides.
icon: shield-check
---

# Overview & Architecture

The Validation component checks FHIR resources against structural rules, FHIRPath invariants,
terminology bindings, profiles, and more, producing a [validation report](reports.md).

## Quick start

<!-- TODO: migrate Quick Start from src/Component/Validation/README.md -->
```php
// $report = $validationService->validate($resource);
```

## Architecture

The service runs a map of focused validators. Each is documented on its own page:

<table data-view="cards">
  <thead>
    <tr><th>Concern</th><th data-card-target data-type="content-ref">Page</th></tr>
  </thead>
  <tbody>
    <tr><td>Cardinality, slices, fixed/pattern values</td><td><a href="structural.md">Structural & Profile</a></td></tr>
    <tr><td>FHIRPath constraint expressions</td><td><a href="invariants.md">FHIRPath Invariants</a></td></tr>
    <tr><td>ValueSet / code bindings</td><td><a href="terminology.md">Terminology & Binding</a></td></tr>
    <tr><td>Reference target profiles</td><td><a href="references.md">References</a></td></tr>
    <tr><td>Quantity & temporal ranges</td><td><a href="ranges.md">Ranges</a></td></tr>
    <tr><td>Extensions, modifiers, obligations</td><td><a href="extensions.md">Extensions</a></td></tr>
    <tr><td>Questionnaire / QuestionnaireResponse</td><td><a href="questionnaire.md">Questionnaire</a></td></tr>
  </tbody>
</table>

## Compatibility & limitations

<!-- TODO: migrate "FHIR Specification Compatibility Matrix" and "Known Limitations" -->

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (Quick Start, Architecture Overview,
     Validator Map, Validation flow, Compatibility Matrix, Known Limitations) -->
