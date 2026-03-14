<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRDateTimeValue;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyVariantMetadata;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Abstract base class for FHIR normalizers providing shared utility methods.
 *
 * Extracts common methods used across all FHIR normalizer implementations to eliminate
 * duplication: XML value unwrapping, primitive extension handling, property type
 * inspection, basic value normalization, and omission rules.
 *
 * @author Ardenexal
 */
abstract class AbstractFHIRNormalizer implements FHIRNormalizerInterface, SerializerAwareInterface
{
    protected ?NormalizerInterface $normalizer = null;

    protected ?DenormalizerInterface $denormalizer = null;

    public function __construct(
        protected readonly FHIRMetadataExtractorInterface $metadataExtractor,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null
    ) {
        $this->normalizer   = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * Called automatically by Symfony's Serializer so recursive normalize/denormalize
     * calls always use the final, fully-wired serializer instance.
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($serializer instanceof NormalizerInterface) {
            $this->normalizer = $serializer;
        }

        if ($serializer instanceof DenormalizerInterface) {
            $this->denormalizer = $serializer;
        }
    }

    /**
     * Check if a value is a FHIR primitive with extension support.
     */
    protected function isPrimitiveWithExtensions(mixed $value): bool
    {
        if (!is_object($value)) {
            return false;
        }

        return $this->metadataExtractor->isPrimitiveType($value);
    }

    /**
     * Normalize a FHIR primitive value, extracting its scalar value and any extensions.
     *
     * Returns ['value' => ..., 'extensions' => ...] or null if $value is not an object.
     * The 'extensions' key is only set when extensions are present and $includeExtensions is true.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    protected function normalizePrimitiveWithExtensions(mixed $value, ?string $format, array $context, bool $includeExtensions = true): ?array
    {
        if (!is_object($value)) {
            return null;
        }

        $result     = [];
        $reflection = new \ReflectionClass($value);

        // Look for value property
        if ($reflection->hasProperty('value')) {
            $valueProperty = $reflection->getProperty('value');
            if ($valueProperty->isInitialized($value)) {
                $raw = $valueProperty->getValue($value);
                // Format DateTimeInterface to string for serialization (dateTime / instant primitives).
                // FHIRDateTimeValue carries the original FHIR partial-date precision.
                if ($raw instanceof \DateTimeInterface) {
                    $raw = $raw instanceof FHIRDateTimeValue
                        ? $raw->format($raw->originalFormat)
                        : $raw->format(\DateTimeInterface::ATOM);
                }

                // XmlEncoder casts PHP booleans to int (true→1, false→0). FHIR XML requires
                // "true"/"false" string literals for boolean attributes.
                if (is_bool($raw) && $format === 'xml') {
                    $raw = $raw ? 'true' : 'false';
                }

                // String decimals: convert back to float for JSON number output.
                // The original string is preserved in the model for XML precision round-trips.
                // Only applies to DecimalPrimitive, identified via the FHIRPrimitive attribute.
                if (is_string($raw) && is_numeric($raw) && $format !== 'xml') {
                    if ($this->isDecimalPrimitive($value)) {
                        $raw = (float) $raw;
                    }
                }

                // For XML, use @ prefix to create attribute; for JSON use plain key
                $valueKey          = ($format === 'xml') ? '@value' : 'value';
                $result[$valueKey] = $raw;
            }
        }

        // Look for extension property
        if ($includeExtensions && $reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions        = $extensionProperty->isInitialized($value) ? $extensionProperty->getValue($value) : null;
            if ($extensions !== null && !empty($extensions)) {
                if ($this->normalizer !== null) {
                    $normalizedExtensions = $this->normalizer->normalize($extensions, $format, $context);
                } else {
                    $normalizedExtensions = $extensions;
                }
                // For XML, use 'extension' (singular); for JSON use 'extensions' (plural)
                $extensionKey          = ($format === 'xml') ? 'extension' : 'extensions';
                $result[$extensionKey] = $normalizedExtensions;
            }
        }

        return $result;
    }

    /**
     * Normalize basic values (fallback when no normalizer is injected).
     *
     * @param array<string, mixed> $context
     */
    protected function normalizeBasicValue(mixed $value, ?string $format, array $context): mixed
    {
        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $normalizedItem = $this->normalizeBasicValue($item, $format, $context);
                if ($normalizedItem !== null) {
                    $result[$key] = $normalizedItem;
                }
            }

            return $result;
        }

        if (is_object($value)) {
            // Basic object normalization - convert public properties to array
            $result     = [];
            $reflection = new \ReflectionClass($value);
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

            foreach ($properties as $property) {
                if (!$property->isInitialized($value)) {
                    continue;
                }
                $propertyValue = $property->getValue($value);
                if ($propertyValue !== null) {
                    $result[$property->getName()] = $this->normalizeBasicValue($propertyValue, $format, $context);
                }
            }

            return $result;
        }

        return $value;
    }

    /**
     * Denormalize basic values (fallback when no denormalizer is injected).
     *
     * @param array<string, mixed> $context
     */
    protected function denormalizeBasicValue(mixed $value, ?string $format, array $context): mixed
    {
        // For basic denormalization, if it's an array representing an object,
        // we can't properly reconstruct it without type information.
        // Return the value as-is for scalar and unknown types.
        return $value;
    }

    /**
     * Get the type of a property from its type hint.
     */
    protected function getPropertyType(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();
        if ($type instanceof \ReflectionNamedType) {
            return $type->getName();
        }

        return null;
    }

    /**
     * Denormalize a single primitive-kind property to its Primitive class.
     *
     * For array primitives, each item (including nulls) is denormalized individually.
     * For non-array primitives, the value is denormalized as a single instance.
     * Falls back to the raw value when the primitive class cannot be resolved.
     *
     * @param \ReflectionClass<object>        $reflection
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function denormalizePrimitiveProperty(
        PropertyMetadata $meta,
        \ReflectionProperty $property,
        \ReflectionClass $reflection,
        mixed $value,
        ?string $format,
        array $context,
        array $metaMap,
    ): mixed {
        if ($this->denormalizer === null) {
            return $value;
        }

        if ($meta->isArray && is_array($value)) {
            $primitiveClass = $this->resolvePrimitiveArrayItemClass($reflection, $meta->fhirType, $metaMap);
            if ($primitiveClass === null) {
                return $value;
            }

            $result = [];
            foreach ($value as $item) {
                $result[] = $this->denormalizer->denormalize($item, $primitiveClass, $format, $context);
            }

            return $result;
        }

        if (!$meta->isArray) {
            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($property);
            if ($primitiveClass === null) {
                return $value;
            }

            return $this->denormalizer->denormalize($value, $primitiveClass, $format, $context);
        }

        return $value;
    }

    /**
     * Return the first non-builtin, non-null type name from a property declaration.
     *
     * Handles both named types (e.g. `?DateTimePrimitive`) and union types
     * (e.g. `StringPrimitive|string|null`), returning the FQCN of the first
     * non-builtin entry — typically the FHIR primitive wrapper class.
     */
    protected function getFirstNonBuiltinTypeFromProperty(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
            return $type->getName();
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $member) {
                if ($member instanceof \ReflectionNamedType && !$member->isBuiltin() && $member->getName() !== 'null') {
                    return $member->getName();
                }
            }
        }

        return null;
    }

    /**
     * Resolve the primitive item class for an array-typed FHIR primitive property.
     *
     * Array primitive properties (e.g. `array $given`) carry no per-item type in their
     * declaration. We resolve the item class by scanning sibling properties that share
     * the same fhirType and are non-array, then extracting the non-builtin type from
     * their union declaration (e.g. `StringPrimitive|string|null` → `StringPrimitive`).
     *
     * @param \ReflectionClass<object>        $class
     * @param array<string, PropertyMetadata> $metaMap
     */
    protected function resolvePrimitiveArrayItemClass(\ReflectionClass $class, string $fhirType, array $metaMap): ?string
    {
        foreach ($metaMap as $siblingName => $siblingMeta) {
            if ($siblingMeta->propertyKind !== 'primitive' || $siblingMeta->isArray || $siblingMeta->fhirType !== $fhirType) {
                continue;
            }

            if (!$class->hasProperty($siblingName)) {
                continue;
            }

            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($class->getProperty($siblingName));
            if ($primitiveClass !== null) {
                return $primitiveClass;
            }
        }

        return null;
    }

    /**
     * Second-pass: apply underscore-prefixed extension data to already-denormalized primitive properties.
     *
     * In FHIR JSON, a property like `_given` carries extension objects that correspond
     * element-by-element to the values in `given`. After the main denormalization loop has
     * already created `StringPrimitive` instances for each `given` item, this method walks
     * all `_name` keys and sets `->extension` on the matching primitive objects.
     *
     * For non-array primitives (e.g. `_family`), the value is `{"extension": [...]}`.
     * For array primitives (e.g. `_given`), the value is `[{"extension": [...]}, null, ...]`.
     *
     * If the corresponding primitive object does not yet exist (base key absent from JSON),
     * a new primitive instance is created with `value = null`.
     *
     * @param \ReflectionClass<object>        $reflection
     * @param array<string, mixed>            $data
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function applyPrimitiveExtensions(
        \ReflectionClass $reflection,
        object $object,
        array $data,
        array $metaMap,
        ?string $format,
        array $context,
    ): void {
        foreach ($data as $elementName => $extData) {
            if (!str_starts_with($elementName, '_')) {
                continue;
            }

            $baseName = substr($elementName, 1);
            $meta     = $metaMap[$baseName] ?? null;

            if ($meta === null || $meta->propertyKind !== 'primitive' || !$reflection->hasProperty($baseName)) {
                continue;
            }

            $property = $reflection->getProperty($baseName);

            if (!$meta->isArray) {
                // Non-array: $extData is {"extension": [...]}
                if (!is_array($extData) || !isset($extData['extension']) || !is_array($extData['extension'])) {
                    continue;
                }

                $current = $property->isInitialized($object) ? $property->getValue($object) : null;

                if (!is_object($current)) {
                    // Property was absent or was a raw scalar — create a primitive with the raw value.
                    $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($property);
                    if ($primitiveClass === null || $this->denormalizer === null) {
                        continue;
                    }

                    $rawValue = is_scalar($current) ? $current : null;
                    $current  = $this->denormalizer->denormalize($rawValue, $primitiveClass, $format, $context);
                    $property->setValue($object, $current);
                }

                $primitiveRefl = new \ReflectionClass($current);
                if ($primitiveRefl->hasProperty('extension')) {
                    $denormalizedExtensions = $this->denormalizeExtensionArray($extData['extension'], $format, $context);
                    $primitiveRefl->getProperty('extension')->setValue($current, $denormalizedExtensions);
                }
            } else {
                // Array: $extData is [{extension:[...]}, null, ...]
                if (!is_array($extData)) {
                    continue;
                }

                $currentArray = $property->isInitialized($object) ? $property->getValue($object) : [];
                if (!is_array($currentArray)) {
                    continue;
                }

                $maxLen = max(count($currentArray), count($extData));

                for ($i = 0; $i < $maxLen; ++$i) {
                    $extEntry         = $extData[$i] ?? null;
                    $hasExtensionData = is_array($extEntry) && isset($extEntry['extension']) && is_array($extEntry['extension']);

                    if (!isset($currentArray[$i]) || !is_object($currentArray[$i])) {
                        if (!$hasExtensionData) {
                            continue;
                        }

                        // No corresponding value — create a null-value primitive.
                        $primitiveClass = $this->resolvePrimitiveArrayItemClass($reflection, $meta->fhirType, $metaMap);
                        if ($primitiveClass === null || $this->denormalizer === null) {
                            continue;
                        }

                        $currentArray[$i] = $this->denormalizer->denormalize(null, $primitiveClass, $format, $context);
                    }

                    $primitiveRefl   = new \ReflectionClass($currentArray[$i]);
                    $extensionProp   = $primitiveRefl->hasProperty('extension') ? $primitiveRefl->getProperty('extension') : null;

                    if ($extensionProp === null) {
                        continue;
                    }

                    if ($hasExtensionData) {
                        $denormalizedExtensions = $this->denormalizeExtensionArray($extEntry['extension'], $format, $context);
                        $extensionProp->setValue($currentArray[$i], $denormalizedExtensions);
                    } elseif (!$extensionProp->isInitialized($currentArray[$i])) {
                        // Initialize to empty array to prevent "must not be accessed before initialization" errors.
                        $extensionProp->setValue($currentArray[$i], []);
                    }
                }

                $property->setValue($object, $currentArray);
            }
        }
    }

    /**
     * Denormalize an extension array from JSON/_property data to Extension objects.
     *
     * @param array<array<string, mixed>|object> $extensionData Raw extension array from JSON
     * @param string|null                        $format        The format being processed ('json', 'xml')
     * @param array<string, mixed>               $context       Denormalization context
     *
     * @return array<array<string, mixed>|object> Array of Extension objects (or raw arrays as fallback)
     */
    protected function denormalizeExtensionArray(array $extensionData, ?string $format, array $context): array
    {
        if ($this->denormalizer === null) {
            // Fallback: return raw data if denormalizer not available
            return $extensionData;
        }

        $denormalizedExtensions = [];
        $extensionClass         = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension';

        foreach ($extensionData as $extension) {
            $denormalizedExtensions[] = is_array($extension)
                ? $this->denormalizer->denormalize($extension, $extensionClass, $format, $context)
                : $extension; // Already an object
        }

        return $denormalizedExtensions;
    }

    /**
     * Return true for PHP built-in types that cannot be passed to the denormalizer.
     */
    protected function isBuiltinType(string $type): bool
    {
        return in_array($type, ['array', 'string', 'int', 'bool', 'float', 'null', 'mixed', 'object', 'callable', 'iterable'], true);
    }

    /**
     * Return true when $obj carries a FHIRPrimitive attribute with primitiveType === 'decimal'.
     *
     * Walks the parent class hierarchy so subclasses of DecimalPrimitive are also matched.
     */
    protected function isDecimalPrimitive(object $obj): bool
    {
        $reflection = new \ReflectionClass($obj);
        do {
            $attributes = $reflection->getAttributes(FHIRPrimitive::class);
            if (!empty($attributes)) {
                return $attributes[0]->newInstance()->primitiveType === 'decimal';
            }
            $reflection = $reflection->getParentClass();
        } while ($reflection !== false);

        return false;
    }

    /**
     * Cast a numeric-string scalar to the appropriate PHP numeric type for JSON output.
     *
     * FHIR decimal properties are stored as ?string to preserve precision (e.g. "1.0" vs "1.00"),
     * but FHIR JSON requires numeric values (not strings). This method converts them back.
     * Integer FHIR types stored as ?int pass through unchanged (already correct PHP type).
     */
    protected function castNumericScalarForJson(mixed $value, ?PropertyMetadata $meta): mixed
    {
        if ($meta === null || $meta->propertyKind !== 'scalar' || !is_string($value) || !is_numeric($value)) {
            return $value;
        }

        return match ($meta->fhirType) {
            'decimal', 'http://hl7.org/fhirpath/System.Decimal' => (float) $value,
            'integer', 'http://hl7.org/fhirpath/System.Integer',
            'unsignedInt', 'positiveInt'                        => (int) $value,
            default                                             => $value,
        };
    }

    /**
     * Unwrap a FHIR XML value encoded as ['@value' => '...', '#' => ''] by Symfony's XmlEncoder.
     *
     * XmlEncoder always adds a '#' key for empty text content alongside XML attributes.
     * Returns the scalar value when the array contains @value and optionally '#', otherwise returns as-is.
     * When the target property type is 'array' and the unwrapped value is not an array, wraps it in [].
     * Also strips XmlEncoder meta-keys (#comment, #) from array-typed property values to prevent
     * them from being re-emitted during serialization.
     */
    protected function unwrapXmlValue(mixed $value, ?string $propertyType = null): mixed
    {
        if (is_array($value) && array_key_exists('@value', $value)) {
            $otherKeys = array_diff(array_keys($value), ['@value', '#']);
            if (empty($otherKeys)) {
                $value = $value['@value'];
            }
        }

        // FHIR XML uses "true"/"false" string literals for boolean attributes.
        // PHP's generic string-to-bool coercion is wrong: (bool)"false" = true (non-empty string).
        // Correctly map "true" → true and "false" → false for bool-typed properties.
        if ($propertyType === 'bool' && is_string($value)) {
            return $value === 'true';
        }

        // XmlEncoder collapses single XML elements into scalars instead of arrays.
        // Wrap in an array when the property expects an array type.
        if ($propertyType === 'array' && !is_array($value)) {
            $value = [$value];
        }

        // For array-typed properties, strip XmlEncoder meta-keys (#comment, # text nodes)
        // that would cause errors if re-emitted to XmlEncoder during serialization.
        if ($propertyType === 'array' && is_array($value)) {
            $value = $this->stripXmlMetaKeys($value);
        }

        return $value;
    }

    /**
     * Recursively remove XmlEncoder meta-keys (keys starting with '#') from a data array.
     *
     * These include '#comment' (XML comments) and '#' (text node content).
     *
     * @param array<mixed> $data
     *
     * @return array<mixed>
     */
    protected function stripXmlMetaKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && str_starts_with($key, '#')) {
                continue;
            }
            $result[$key] = is_array($value) ? $this->stripXmlMetaKeys($value) : $value;
        }

        return $result;
    }

    /**
     * Clean XML encoder artifacts from denormalized data.
     *
     * Symfony's XmlEncoder adds prefixes and meta-keys to the decoded array:
     * - XML attributes become keys with @ prefix (url="X" → @url)
     * - Empty text nodes become # keys
     *
     * This method strips these artifacts to prepare data for property mapping.
     *
     * @param array<string, mixed> $data Raw data from XmlEncoder
     *
     * @return array<string, mixed> Cleaned data ready for denormalization
     */
    protected function cleanXmlArtifacts(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            // Handle non-string keys (numeric array indices)
            // @phpstan-ignore-next-line function.alreadyNarrowedType (false positive: keys can be int|string in foreach)
            if (!is_string($key)) {
                $cleaned[$key] = is_array($value) ? $this->cleanXmlArtifacts($value) : $value;
                continue;
            }

            // Strip @ prefix from keys (XML attributes)
            $cleanKey = str_starts_with($key, '@') ? substr($key, 1) : $key;

            // Skip # empty text nodes
            if ($cleanKey === '#') {
                continue;
            }

            // Recursively clean nested arrays
            if (is_array($value)) {
                $value = $this->cleanXmlArtifacts($value);
            }

            $cleaned[$cleanKey] = $value;
        }

        return $cleaned;
    }

    /**
     * Return the compiled property metadata map for the given object's class.
     *
     * Warm-cache hit from PropertyMetadataProvider on every call after the first.
     *
     * @return array<string, PropertyMetadata>
     */
    protected function getPropertyMetadataMap(object $object): array
    {
        return $this->metadataExtractor->getPropertyMetadataProvider()->getPropertyMetadata(get_class($object));
    }

    /**
     * Resolve the concrete propertyKind, JSON/XML key, and FHIR type for a choice element value.
     *
     * Iterates over the compiled variant list and matches the runtime value by PHP type.
     * Returns ['', '', ''] when no variant matches (caller should fall back to legacy handling).
     *
     * @param list<PropertyVariantMetadata> $variants
     *
     * @return array{0: string, 1: string, 2: string} [propertyKind, jsonKey, fhirType]
     */
    protected function resolveChoiceVariant(mixed $value, array $variants): array
    {
        /** @var array<string, string> */
        static $phpToGettype = ['bool' => 'boolean', 'int' => 'integer', 'float' => 'double', 'string' => 'string'];

        foreach ($variants as $variant) {
            if ($variant->isBuiltin) {
                if (gettype($value) === ($phpToGettype[$variant->phpType] ?? '')) {
                    return [$variant->propertyKind, $variant->jsonKey, $variant->fhirType];
                }
            } elseif (is_object($value) && $value instanceof $variant->phpType) {
                return [$variant->propertyKind, $variant->jsonKey, $variant->fhirType];
            }
        }

        return ['', '', ''];
    }

    /**
     * Find the property name, PHP type, and FHIR type for a choice element by its JSON/XML key.
     *
     * Reverse lookup: given an element name like 'valueQuantity', find the base property
     * name ('value'), the concrete PHP type, and the FHIR type for that variant.
     *
     * @param array<string, PropertyMetadata> $metaMap    The property metadata map
     * @param string                          $elementKey The JSON/XML element name (e.g., 'valueQuantity')
     *
     * @return array{0: string, 1: string, 2: string}|null [propertyName, phpType, fhirType] or null if not found
     */
    protected function findChoicePropertyByKey(array $metaMap, string $elementKey): ?array
    {
        foreach ($metaMap as $propertyName => $meta) {
            if ($meta->isChoice && !empty($meta->variants)) {
                foreach ($meta->variants as $variant) {
                    if ($variant->jsonKey === $elementKey) {
                        return [$propertyName, $variant->phpType, $variant->fhirType];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Check if a value should be omitted according to FHIR rules and context configuration.
     */
    protected function shouldOmitValue(mixed $value, FHIRSerializationContext $context): bool
    {
        // Omit null values if configured
        if ($value === null && $context->omitNullValues) {
            return true;
        }

        // Omit empty arrays if configured
        if (is_array($value) && empty($value) && $context->omitEmptyArrays) {
            return true;
        }

        // Omit empty strings (always for FHIR compliance)
        if (is_string($value) && $value === '') {
            return true;
        }

        return false;
    }

    /**
     * Encode an XmlEncoder-decoded XHTML array back to an XML string using DOMDocument.
     *
     * XmlEncoder::encode cannot reliably round-trip repeated sibling elements
     * (e.g. two <p> tags) — it wraps them in <item> elements. DOMDocument handles
     * this correctly and is used here instead.
     *
     * The $elementName parameter names the root element (typically 'div' for FHIR xhtml).
     * The $data array uses XmlEncoder conventions: '@key' for attributes, '#' for text nodes,
     * and list arrays for repeated sibling elements.
     *
     * @param array<string, mixed> $data
     */
    protected function encodeXhtmlToString(array $data, string $elementName): string
    {
        $dom  = new \DOMDocument('1.0', 'UTF-8');
        $root = $dom->createElement($elementName);
        $dom->appendChild($root);
        $this->buildDomFromArray($dom, $root, $data);

        return $dom->saveXML($root) ?: '';
    }

    /**
     * Recursively populate a DOMElement from an XmlEncoder-style data array.
     *
     * @param array<array-key, mixed> $data
     */
    private function buildDomFromArray(\DOMDocument $dom, \DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                continue; // skip numeric keys (handled by list branch in parent call)
            }

            // Skip XmlEncoder meta-keys that are not mappable to DOM nodes
            if (str_starts_with($key, '#') && $key !== '#' && $key !== '#text') {
                // '#comment', '#document', etc. — skip
                continue;
            }

            if (str_starts_with($key, '@')) {
                // XML attribute (e.g. @xmlns, @href)
                $parent->setAttribute(substr($key, 1), (string) $value);
            } elseif ($key === '#' || $key === '#text') {
                // Text node content.
                // '#' = single text node; '#text' = multiple text nodes in mixed content
                // (XmlEncoder uses #text when text and element nodes coexist as siblings).
                if (is_array($value)) {
                    foreach ($value as $textItem) {
                        if ($textItem !== '' && $textItem !== null) {
                            $parent->appendChild($dom->createTextNode((string) $textItem));
                        }
                    }
                } elseif ($value !== '' && $value !== null) {
                    $parent->appendChild($dom->createTextNode((string) $value));
                }
            } elseif (is_array($value) && array_is_list($value)) {
                // Multiple sibling elements with the same tag name (e.g. two <p> elements)
                foreach ($value as $item) {
                    $child = $dom->createElement($key);
                    $parent->appendChild($child);
                    if (is_array($item)) {
                        $this->buildDomFromArray($dom, $child, $item);
                    } elseif ($item !== '' && $item !== null) {
                        $child->appendChild($dom->createTextNode((string) $item));
                    }
                }
            } elseif (is_array($value)) {
                // Single child element with sub-structure
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                $this->buildDomFromArray($dom, $child, $value);
            } else {
                // Single child element with text content (or self-closing like <br/>)
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                if ($value !== '' && $value !== null) {
                    $child->appendChild($dom->createTextNode((string) $value));
                }
            }
        }
    }

    /**
     * Decode an XHTML XML element string back to the array format XmlEncoder expects.
     *
     * Used when XhtmlPrimitive.value holds a raw XML string (e.g. from JSON deserialization
     * where FHIR stores xhtml as a string). Parses the string and returns the content of the
     * root element as an associative array (attributes keyed with @ prefix, child elements
     * keyed by tag name) so that XmlEncoder can re-emit it as proper nested XML elements.
     *
     * The decoded array is post-processed so that lists of scalar values are converted to
     * lists of text-node objects (e.g. `'p' => ['First', 'Second']` becomes
     * `'p' => [['#' => 'First'], ['#' => 'Second']]`). This prevents XmlEncoder from
     * wrapping repeated sibling elements in `<item>` tags when re-encoding.
     *
     * @return array<string, mixed>
     */
    protected function decodeXhtmlToArray(string $xmlString): array
    {
        $xmlEncoder = new XmlEncoder();
        $decoded    = $xmlEncoder->decode($xmlString, 'xml');

        if (!is_array($decoded)) {
            return [];
        }

        return $this->transformXhtmlArrayForReencoding($decoded);
    }

    /**
     * Recursively transform an XmlEncoder-decoded XHTML array so it can be re-encoded correctly.
     *
     * XmlEncoder incorrectly wraps repeated sibling elements in <item> tags when the value is
     * a list of scalars (e.g. `'p' => ['First', 'Second']`). Converting each scalar to an
     * associative array with a '#' text-node key fixes this: XmlEncoder then emits repeated
     * `<p>` elements correctly.
     *
     * Also maps `#text` (XmlEncoder's mixed-content key) to `#` (standard text-node key).
     *
     * @param array<string, mixed> $data
     *
     * @return array<array-key, mixed>
     */
    private function transformXhtmlArrayForReencoding(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            // #text is XmlEncoder's key for multiple text nodes in mixed content. Map to '#'.
            if ($key === '#text') {
                $combined        = is_array($value) ? implode('', $value) : (string) $value;
                $result['#']     = ($result['#'] ?? '') . $combined;
                continue;
            }

            if (is_array($value) && array_is_list($value)) {
                // List of items: wrap plain scalars in ['#' => scalar] so XmlEncoder
                // produces repeated <tag>text</tag> elements instead of <tag><item>...</item></tag>.
                $transformed = [];
                foreach ($value as $item) {
                    if (is_scalar($item)) {
                        $transformed[] = ['#' => (string) $item];
                    } elseif (is_array($item)) {
                        $transformed[] = $this->transformXhtmlArrayForReencoding($item);
                    } else {
                        $transformed[] = $item;
                    }
                }
                $result[$key] = $transformed;
            } elseif (is_array($value)) {
                $result[$key] = $this->transformXhtmlArrayForReencoding($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
