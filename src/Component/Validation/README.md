# FHIR Validation Component

Validates FHIR PHP model objects against the specification and Implementation Guide profiles —
structural constraints, FHIRPath invariants, terminology bindings, references, and more — producing
a structured validation report.

```bash
composer require ardenexal/fhir-validation
```

## Documentation

Full documentation lives in the centralised [documentation site](../../../docs/README.md):

- [Overview & Architecture](../../../docs/validation/overview.md)
- [Structural & Profile Validation](../../../docs/validation/structural.md)
- [FHIRPath Invariant Validation](../../../docs/validation/invariants.md)
- [Terminology & Binding Validation](../../../docs/validation/terminology.md)
- [Reference & Target Profile Validation](../../../docs/validation/references.md)
- [Quantity & Temporal Range Validation](../../../docs/validation/ranges.md)
- [Extensions, Modifiers & Obligations](../../../docs/validation/extensions.md)
- [Runtime Profiles](../../../docs/validation/runtime-profiles.md)
- [Questionnaire Validation](../../../docs/validation/questionnaire.md)
- [The $validate Operation](../../../docs/validation/operation-outcome.md)
- [Configuration](../../../docs/validation/configuration.md)
- [Validation Reports & Violation Codes](../../../docs/validation/reports.md)

## License

Released under the MIT License. See [LICENSE](../../../LICENSE).
