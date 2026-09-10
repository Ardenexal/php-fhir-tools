# FHIR Metadata Component

Owns the answer to "what does this FHIR class look like" — the attributes generated models carry, the
type and property metadata read back off them, and the Implementation Guide type registry.
Serialization, Validation and FHIRPath all read this one component rather than each reflecting over
model classes with their own copy of the question.

```bash
composer require ardenexal/fhir-metadata
```

## Documentation

This component has no guide of its own. These pages in the centralised
[documentation site](../../../docs/README.md) document its surface where other components consume it:

- [Choosing the Right Package](../../../docs/getting-started/packages.md)
- [Overview & Architecture](../../../docs/validation/overview.md)
- [Extensions, Modifiers & Obligations](../../../docs/validation/extensions.md)

## License

Released under the MIT License. See [LICENSE](../../../LICENSE).
