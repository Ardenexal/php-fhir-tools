<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyVariantMetadata;
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
                // Format DateTimeInterface to string for serialization (dateTime / instant primitives)
                if ($raw instanceof \DateTimeInterface) {
                    $raw = $raw->format(\DateTimeInterface::ATOM);
                }
                $result['value'] = $raw;
            }
        }

        // Look for extension property
        if ($includeExtensions && $reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions        = $extensionProperty->isInitialized($value) ? $extensionProperty->getValue($value) : null;
            if ($extensions !== null && !empty($extensions)) {
                if ($this->normalizer !== null) {
                    $result['extensions'] = $this->normalizer->normalize($extensions, $format, $context);
                } else {
                    $result['extensions'] = $extensions;
                }
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
     * Return true for PHP built-in types that cannot be passed to the denormalizer.
     */
    protected function isBuiltinType(string $type): bool
    {
        return in_array($type, ['array', 'string', 'int', 'bool', 'float', 'null', 'mixed', 'object', 'callable', 'iterable'], true);
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
     * Resolve the PHP element class for an array-typed FHIR property.
     *
     * Primary path: return PropertyMetadata::$phpClass, which the code generator populates
     * with the FQCN of the element type for array complex/backbone properties.
     *
     * Fallback path: heuristic namespace derivation for models generated before phpClass was
     * added to FHIR_PROPERTY_MAP. Remove once all models have been regenerated.
     */
    protected function resolveArrayElementClass(string $ownerClass, string $propertyName, PropertyMetadata $meta): ?string
    {
        if ($meta->phpClass !== null) {
            return $meta->phpClass;
        }

        // Legacy fallback: heuristic derivation from owner class namespace + property metadata.
        if (!in_array($meta->propertyKind, ['complex', 'backbone'], true)) {
            return null;
        }

        $version = null;
        if (preg_match('/\\\\Models\\\\(R\\d+[AB]?)\\\\/', $ownerClass, $m)) {
            $version = $m[1];
        }

        if ($version === null) {
            return null;
        }

        if ($meta->propertyKind === 'complex') {
            $candidate = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\DataType\\{$meta->fhirType}";

            return class_exists($candidate) ? $candidate : null;
        }

        $parts            = explode('\\', $ownerClass);
        $ownerShortName   = end($parts);
        $resourceTypeName = str_ends_with($ownerShortName, 'Resource')
            ? substr($ownerShortName, 0, -8)
            : $ownerShortName;
        $ownerNamespace   = implode('\\', array_slice($parts, 0, -1));
        $backboneName     = $resourceTypeName . ucfirst($propertyName);
        $candidate        = "{$ownerNamespace}\\{$resourceTypeName}\\{$backboneName}";

        return class_exists($candidate) ? $candidate : null;
    }

    /**
     * Denormalize all elements of a raw array property into typed objects.
     *
     * Handles both JSON (always a list) and XML (single element = assoc array, multiple = list).
     *
     * @param mixed                $rawValue     The raw value from the deserializer
     * @param string               $elementClass The PHP class to denormalize each element to
     * @param string|null          $format       'json', 'xml', or null
     * @param array<string, mixed> $context      Symfony serializer context
     *
     * @return array<mixed>
     */
    protected function denormalizeArrayProperty(mixed $rawValue, string $elementClass, ?string $format, array $context): array
    {
        if (!is_array($rawValue) || $this->denormalizer === null) {
            return is_array($rawValue) ? $rawValue : [];
        }

        // JSON and XML (multiple elements): already a list array — denormalize each item
        if (array_is_list($rawValue)) {
            $result = [];
            foreach ($rawValue as $item) {
                $result[] = is_array($item)
                    ? $this->denormalizer->denormalize($item, $elementClass, $format, $context)
                    : $item;
            }

            return $result;
        }

        // XML single element: an assoc array representing one sub-object — wrap after denormalizing
        return [$this->denormalizer->denormalize($rawValue, $elementClass, $format, $context)];
    }

    /**
     * Resolve the concrete propertyKind and JSON/XML key for a choice element value.
     *
     * Iterates over the compiled variant list and matches the runtime value by PHP type.
     * Returns ['', ''] when no variant matches (caller should fall back to legacy handling).
     *
     * @param list<PropertyVariantMetadata> $variants
     *
     * @return array{0: string, 1: string} [propertyKind, jsonKey]
     */
    protected function resolveChoiceVariant(mixed $value, array $variants): array
    {
        /** @var array<string, string> */
        static $phpToGettype = ['bool' => 'boolean', 'int' => 'integer', 'float' => 'double', 'string' => 'string'];

        foreach ($variants as $variant) {
            if ($variant->isBuiltin) {
                if (gettype($value) === ($phpToGettype[$variant->phpType] ?? '')) {
                    return [$variant->propertyKind, $variant->jsonKey];
                }
            } elseif (is_object($value) && $value instanceof $variant->phpType) {
                return [$variant->propertyKind, $variant->jsonKey];
            }
        }

        return ['', ''];
    }

    /**
     * Find the property name and PHP type for a choice element by its JSON/XML key.
     *
     * Reverse lookup: given an element name like 'valueQuantity', find the base property
     * name ('value') and the concrete PHP type for that variant.
     *
     * @param array<string, PropertyMetadata> $metaMap    The property metadata map
     * @param string                          $elementKey The JSON/XML element name (e.g., 'valueQuantity')
     *
     * @return array{0: string, 1: string}|null [propertyName, phpType] or null if not found
     */
    protected function findChoicePropertyByKey(array $metaMap, string $elementKey): ?array
    {
        foreach ($metaMap as $propertyName => $meta) {
            if ($meta->isChoice && !empty($meta->variants)) {
                foreach ($meta->variants as $variant) {
                    if ($variant->jsonKey === $elementKey) {
                        return [$propertyName, $variant->phpType];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Attempt to denormalize a value for a union-typed property by trying each
     * non-built-in candidate type in the union.
     *
     * Returns the original value when no candidate type supports denormalization.
     * Used after `unwrapXmlValue` when the result is still an array — for example,
     * a FHIR primitive XML element that carries child extension elements alongside
     * its `@value` attribute (e.g. `['@value' => 'du Marché', 'extension' => [...]]`).
     *
     * @param array<string, mixed> $context
     */
    protected function tryDenormalizeForUnionType(\ReflectionProperty $property, mixed $value, ?string $format, array $context): mixed
    {
        if ($this->denormalizer === null) {
            return $value;
        }

        $type = $property->getType();
        if (!$type instanceof \ReflectionUnionType) {
            return $value;
        }

        foreach ($type->getTypes() as $t) {
            if (!$t instanceof \ReflectionNamedType || $t->isBuiltin()) {
                continue;
            }

            $candidateType = $t->getName();
            if ($this->denormalizer->supportsDenormalization($value, $candidateType, $format, $context)) {
                return $this->denormalizer->denormalize($value, $candidateType, $format, $context);
            }
        }

        return $value;
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
}
