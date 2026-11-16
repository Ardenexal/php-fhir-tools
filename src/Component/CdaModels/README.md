# CDA Models Component

Pre-generated PHP classes for the CDA R2 logical models — HL7 `cda.uv.core` plus the AU Digital
Health extensions — produced by the [CodeGeneration](../CodeGeneration/README.md) component from CDA
StructureDefinitions. CDA is an XML-only format, so these classes round-trip as XML and the JSON path
refuses them rather than emitting something the specification does not define.

```bash
composer require ardenexal/cda-sd-models
```

## Documentation

Full documentation lives in the centralised [documentation site](../../../docs/README.md):

- [Generating CDA Logical Models](../../../docs/code-generation/cda.md)

## License

Released under the MIT License. See [LICENSE](../../../LICENSE).
