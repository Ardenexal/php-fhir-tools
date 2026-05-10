# Normalizer Refactor Plan

GitHub Issue: https://github.com/Ardenexal/php-fhir-tools/issues/59

## Goal

Split `src/Component/Serialization/src/Normalizer/` into `Common/`, `Json/`, and `Xml/` subfolders so that adding a new serialization format only requires a new folder with 4 classes and compiler pass wiring — without touching any existing files.

---

## Target Structure

```
Normalizer/
├── Common/
│   ├── AbstractFHIRNormalizer.php       ← new namespace + added protected helpers
│   └── FHIRNormalizerInterface.php      ← new namespace only
├── Json/
│   ├── FHIRResourceJsonNormalizer.php
│   ├── FHIRComplexTypeJsonNormalizer.php
│   ├── FHIRBackboneElementJsonNormalizer.php
│   └── FHIRPrimitiveTypeJsonNormalizer.php
└── Xml/
    ├── FHIRResourceXmlNormalizer.php
    ├── FHIRComplexTypeXmlNormalizer.php
    ├── FHIRBackboneElementXmlNormalizer.php
    └── FHIRPrimitiveTypeXmlNormalizer.php
```

Format routing via `supportsNormalization()` / `supportsDenormalization()`:
- **Json normalizers:** return `false` when `$format === 'xml'`
- **Xml normalizers:** return `false` when `$format !== 'xml'`

---

## Duplication Fixes

| Issue | Fix |
|---|---|
| `extractResourceElementName()` duplicated in `FHIRResourceNormalizer` + `FHIRBackboneElementNormalizer` | Move to `AbstractFHIRNormalizer` as `protected` |
| `normalizeExtensions()` private in Backbone, other normalizers do it inline | Move to `AbstractFHIRNormalizer` as `protected` |
| Polymorphic XML resource wrap block copy-pasted in Resource + Backbone | Extract `normalizePolymorphicResourcesXml()` to Abstract |
| `BackboneElementNormalizer::resolveResourceType()` hardcodes `['R4','R4B','R5']` loop | Inject `FHIRTypeResolverInterface` into `FHIRBackboneElementXmlNormalizer` instead |
| Primitive validation methods (`validateDecimal`, `createPrimitiveInstance`, etc.) needed by both Json + Xml primitive normalizers | Move to `AbstractFHIRNormalizer` as `protected` |
| `handleUnknownProperty()` private in `FHIRResourceNormalizer`, used in both JSON + XML denormalize paths | Move to `AbstractFHIRNormalizer` as `protected` |
| `['@value' => is_bool ... (string)]` expression repeated throughout XML normalizers | Extract `wrapScalarForXml()` helper to Abstract |

---

## Phase 1 — `Common/AbstractFHIRNormalizer.php`

**Namespace:** `Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common`

Keep all existing methods. Add the following new `protected` methods:

### Moved from concrete normalizers

```php
// From FHIRResourceNormalizer::extractResourceElementName() and FHIRBackboneElementNormalizer::extractResourceElementName()
protected function extractResourceElementName(mixed $value): ?string

// From FHIRBackboneElementNormalizer::normalizeExtensions()
protected function normalizeExtensions(mixed $extensions, ?string $format, array $context): ?array

// From FHIRResourceNormalizer::handleUnknownProperty()
protected function handleUnknownProperty(string $propertyName, mixed $value, string $policy, object $object, ?string $elementPath = null): void
```

### New helpers

```php
// Replaces the polymorphic XML wrap block copy-pasted in Resource + Backbone normalizeForXML()
// Array: wraps each item as [$resourceType => $normalized]
// Single object: wraps as [$resourceType => $normalized]
// Returns null if nothing to normalize
protected function normalizePolymorphicResourcesXml(mixed $value, PropertyMetadata $meta, array $context): mixed

// Replaces repeated inline: ['@value' => is_bool($v) ? 'true'/'false' : (string)$v]
protected function wrapScalarForXml(mixed $value): array
```

### Primitive validation methods (moved from FHIRPrimitiveTypeNormalizer)

```php
protected function findFHIRPrimitiveAttribute(string $type): ?FHIRPrimitive
protected function hasFHIRPrimitiveAttribute(string $type): bool
protected function createPrimitiveInstance(string $type, mixed $value, mixed $extensions, ?string $format = null, array $context = []): mixed
protected function validateAndConvertValue(mixed $value, string $type): mixed
protected function validateString(mixed $value): ?string
protected function validateInteger(mixed $value): ?int
protected function validateDecimal(mixed $value): ?string  // returns numeric-string|null
protected function validateBoolean(mixed $value): ?bool
protected function parseTemporalValue(mixed $value, string $class): ?FHIRTemporalValue
```

**New imports required:**
```php
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
```

---

## Phase 2 — `Common/FHIRNormalizerInterface.php`

Only change: namespace → `Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common`

---

## Phase 3 — `Json/` normalizers

All Json normalizers extend `Common\AbstractFHIRNormalizer` and implement `Common\FHIRNormalizerInterface`.

### `FHIRResourceJsonNormalizer`
- **Constructor:** `metadataExtractor` + `typeResolver` + optional `normalizer`, `denormalizer`, `fhirVersion`, `igTypeRegistry`
- **`supportsNormalization()`:** `if ($format === 'xml') return false;` → then `isResource()` check
- **`supportsDenormalization()`:** `if ($format === 'xml') return false;` → then walk `FhirResource` attribute hierarchy
- **`normalize()`:** inline the existing `normalizeForJSON()` logic
- **`denormalize()`:** inline the existing `denormalizeFromJSON()` logic
- **Private:** `normalizeArrayWithExtensions()`

### `FHIRComplexTypeJsonNormalizer`
- **Constructor:** `metadataExtractor` + `typeResolver` + optional args
- **`supportsNormalization()`:** `if ($format === 'xml') return false;` → existing complex type attribute walk
- **`supportsDenormalization()`:** `if ($format === 'xml') return false;` → existing `FHIRComplexType` / `FHIRExtensionDefinition` check
- **`normalize()`:** inline existing `normalizeForJSON()` logic
- **`denormalize()`:** JSON-specific only — skip `_` prefixed keys, no `@`-attr handling, no `unwrapXmlValue`, call `applyPrimitiveExtensions()` at end
- **Private:** `isChoiceElement()`, `normalizeChoiceElement()`

### `FHIRBackboneElementJsonNormalizer`
- **Constructor:** `metadataExtractor` + optional args — **no `FHIRTypeResolverInterface`** (not needed for JSON)
- **`supportsNormalization()`:** `if ($format === 'xml') return false;` → `FHIRBackboneElement` attribute check
- **`supportsDenormalization()`:** same guard
- **`normalize()`:** inline existing `normalizeForJSON()` logic — uses `normalizeExtensions()` from Abstract
- **`denormalize()`:** JSON backbone — no XML unwrapping, no `extractResourceElementName`, calls `applyPrimitiveExtensions()`

### `FHIRPrimitiveTypeJsonNormalizer`
- **Constructor:** `metadataExtractor` + optional args
- **`supportsNormalization()`:** `if ($format === 'xml') return false;` → `hasFHIRPrimitiveAttribute()`
- **`supportsDenormalization()`:** same guard
- **`normalize()`:** inline existing `normalizeForJSON()` logic
- **`denormalize()`:** JSON path from `denormalizeFromArray()` only (no XML branch)
- Uses primitive validation methods from `AbstractFHIRNormalizer`

---

## Phase 4 — `Xml/` normalizers

All Xml normalizers extend `Common\AbstractFHIRNormalizer` and implement `Common\FHIRNormalizerInterface`.

### `FHIRResourceXmlNormalizer`
- **Constructor:** same as `FHIRResourceJsonNormalizer`
- **`supportsNormalization()`:** `if ($format !== 'xml') return false;` → `isResource()` check
- **`supportsDenormalization()`:** `if ($format !== 'xml') return false;` → `FhirResource` attribute walk
- **`normalize()`:** inline existing `normalizeForXML()` — use `normalizePolymorphicResourcesXml()` + `wrapScalarForXml()`
- **`denormalize()`:** inline existing `denormalizeFromXML()` — use `extractResourceElementName()` from Abstract
- **Private:** `normalizeArrayForXML()`

### `FHIRComplexTypeXmlNormalizer`
- **Constructor:** `metadataExtractor` + `typeResolver` + optional args (typeResolver needed for resource property resolution)
- **`supportsNormalization()`:** `if ($format !== 'xml') return false;` → existing complex type check
- **`supportsDenormalization()`:** `if ($format !== 'xml') return false;` → existing check
- **`normalize()`:** inline existing `normalizeForXML()` — use `normalizePolymorphicResourcesXml()` + `wrapScalarForXml()`
- **`denormalize()`:** XML-specific — `@`-attr handling, xhtml decode, `unwrapXmlValue`, `array_is_list` checks, resource element resolution, **no** `applyPrimitiveExtensions`
- **Private:** `encodeXhtmlToString()`, `buildDomFromArray()`, `decodeXhtmlToArray()`, `transformXhtmlArrayForReencoding()`, `isChoiceElement()`, `normalizeChoiceElement()`

### `FHIRBackboneElementXmlNormalizer`
- **Constructor:** `metadataExtractor` + **`FHIRTypeResolverInterface $typeResolver`** + optional args (replaces hardcoded `resolveResourceType()`)
- **`supportsNormalization()`:** `if ($format !== 'xml') return false;` → backbone attribute check
- **`supportsDenormalization()`:** same guard
- **`normalize()`:** inline existing `normalizeForXML()` — use `normalizePolymorphicResourcesXml()` + `normalizeExtensions()` from Abstract
- **`denormalize()`:** XML backbone — uses `extractResourceElementName()` + `$this->typeResolver->resolveResourceType()`, `unwrapXmlValue`, `array_is_list` checks

### `FHIRPrimitiveTypeXmlNormalizer`
- **Constructor:** same as Json variant
- **`supportsNormalization()`:** `if ($format !== 'xml') return false;` → `hasFHIRPrimitiveAttribute()`
- **`supportsDenormalization()`:** same guard
- **`normalize()`:** inline existing `normalizeForXML()` logic
- **`denormalize()`:** XML path from `denormalizeFromArray()` only (no JSON branch)
- Uses primitive validation methods from `AbstractFHIRNormalizer`

---

## Phase 5 — Wiring updates

### `FHIRVersionedSerializerPass.php`

Register 8 normalizers per version instead of 4. New service IDs:

```
fhir.normalizer.resource.json.{v}      → FHIRResourceJsonNormalizer
fhir.normalizer.resource.xml.{v}       → FHIRResourceXmlNormalizer
fhir.normalizer.complex_type.json.{v}  → FHIRComplexTypeJsonNormalizer
fhir.normalizer.complex_type.xml.{v}   → FHIRComplexTypeXmlNormalizer
fhir.normalizer.primitive.json.{v}     → FHIRPrimitiveTypeJsonNormalizer
fhir.normalizer.primitive.xml.{v}      → FHIRPrimitiveTypeXmlNormalizer
fhir.normalizer.backbone.json.{v}      → FHIRBackboneElementJsonNormalizer
fhir.normalizer.backbone.xml.{v}       → FHIRBackboneElementXmlNormalizer
```

`FHIRBackboneElementXmlNormalizer` gets an extra `FHIRTypeResolverInterface` reference as second argument.

Serializer receives all 8 normalizer references. Suggested order (more specific first):
`resource-json, resource-xml, complex-json, complex-xml, primitive-json, primitive-xml, backbone-json, backbone-xml`

### `FHIRSerializationService.php` — `createWithIG()`

Instantiate 8 normalizers. `FHIRBackboneElementXmlNormalizer` gets `$typeResolver` as second constructor arg.

---

## Phase 6 — Delete old files

```
src/Component/Serialization/src/Normalizer/AbstractFHIRNormalizer.php
src/Component/Serialization/src/Normalizer/FHIRNormalizerInterface.php
src/Component/Serialization/src/Normalizer/FHIRResourceNormalizer.php
src/Component/Serialization/src/Normalizer/FHIRComplexTypeNormalizer.php
src/Component/Serialization/src/Normalizer/FHIRBackboneElementNormalizer.php
src/Component/Serialization/src/Normalizer/FHIRPrimitiveTypeNormalizer.php
```

---

## Phase 7 — Quality checks

```bash
composer phpstan-ai:serialization
composer test-ai:serialization
```

All 82/82 serialization spec tests must pass. PHPStan level 8 clean.

---

## Constraints

- Internal refactor — breaking namespace changes are acceptable
- No behaviour changes — round-trip serialization must produce identical output
- Tests only need to pass at the end (things will be broken mid-refactor)