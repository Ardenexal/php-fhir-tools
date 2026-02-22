<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Normalizer for FHIR complex type classes (Address, HumanName, etc.).
 *
 * This normalizer handles serialization and deserialization of FHIR complex types
 * following the official FHIR JSON specification. It supports nested object
 * serialization, choice element (value[x]) patterns, complex type extensions,
 * and polymorphic complex type resolution.
 *
 * @author Ardenexal
 */
class FHIRComplexTypeNormalizer implements FHIRNormalizerInterface, SerializerAwareInterface
{
    private ?NormalizerInterface $normalizer;

    private ?DenormalizerInterface $denormalizer;

    public function __construct(
        private readonly FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
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
     * {@inheritDoc}
     */
    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|string|int|float|bool|\ArrayObject<string, mixed>|null
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Expected object, got ' . gettype($object));
        }

        if (!$this->metadataExtractor->isComplexType($object)) {
            throw new InvalidArgumentException('Object is not a FHIR complex type');
        }

        // Handle XML format
        if ($format === 'xml') {
            return $this->normalizeForXML($object, $context);
        }

        // Handle JSON format (default)
        return $this->normalizeForJSON($object, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (!is_object($data)) {
            return false;
        }

        return $this->metadataExtractor->isComplexType($data);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        // Use type resolver for polymorphic complex type resolution
        $resolvedType = $this->typeResolver->resolveComplexType($data, $context);
        if ($resolvedType === null) {
            // Fall back to the provided type if resolver can't determine the type
            $resolvedType = $type;
        }

        // If the resolver returned an incompatible type (e.g. a short heuristic name like
        // 'FHIRPeriod' without a namespace), fall back to the expected type from the property
        // declaration. This avoids false-positive rejections when the type resolver guesses wrong.
        if ($resolvedType !== $type && !is_subclass_of($resolvedType, $type)) {
            $resolvedType = $type;
        }

        try {
            /** @var class-string $resolvedType */
            $reflection = new \ReflectionClass($resolvedType);
            $object     = $reflection->newInstanceWithoutConstructor();

            // Set properties from the data
            foreach ($data as $propertyName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($propertyName, '_')) {
                    continue;
                }

                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);

                    // Handle choice elements (value[x] pattern)
                    if ($this->isChoiceElement($propertyName)) {
                        $denormalizedValue = $this->denormalizeChoiceElement($propertyName, $value, $format, $context);
                    } else {
                        // Always use the denormalizer to create properly-typed instances.
                        // _property extension keys are implicitly skipped at the loop level.
                        if ($this->denormalizer !== null) {
                            $propertyType = $this->getPropertyType($property);
                            if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, $format, $context);
                            } else {
                                // For XML, Symfony XmlEncoder wraps primitive values as ['@value' => '...', '#' => ''].
                                // Unwrap before assigning to string/union-typed properties.
                                $denormalizedValue = $format === 'xml' ? $this->unwrapXmlValue($value, $propertyType) : $value;
                            }
                        } else {
                            $denormalizedValue = $this->denormalizeBasicValue($value, $format, $context);
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (!is_array($data)) {
            return false;
        }

        // Check if the type is a FHIR complex type class
        try {
            /** @var class-string $type */
            $reflection = new \ReflectionClass($type);
            $attributes = $reflection->getAttributes(FHIRComplexType::class);

            return !empty($attributes);
        } catch (\ReflectionException) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        // This normalizer supports any class with the FHIRComplexType attribute
        return ['object' => true];
    }

    /**
     * Check if a property name represents a choice element (value[x] pattern)
     */
    private function isChoiceElement(string $propertyName): bool
    {
        // Choice elements typically start with 'value' followed by a type suffix
        return str_starts_with($propertyName, 'value') && strlen($propertyName) > 5;
    }

    /**
     * Normalize a choice element with proper type suffix handling
     *
     * @param array<string, mixed> $context
     */
    private function normalizeChoiceElement(string $propertyName, mixed $value, ?string $format, array $context): mixed
    {
        // For choice elements, we need to include the type suffix in the property name
        // The property name already contains the type suffix (e.g., valueString, valueInteger)

        if ($this->normalizer !== null) {
            return $this->normalizer->normalize($value, $format, $context);
        }

        return $this->normalizeBasicValue($value, $format, $context);
    }

    /**
     * Denormalize a choice element with proper type handling
     *
     * @param array<string, mixed> $context
     */
    private function denormalizeChoiceElement(string $propertyName, mixed $value, ?string $format, array $context): mixed
    {
        // For choice elements, the property name contains the type information
        // Extract the type from the property name (e.g., valueString -> string)
        $typeSuffix = substr($propertyName, 5); // Remove 'value' prefix

        if ($this->denormalizer !== null) {
            // Try to determine the appropriate type for denormalization
            $targetType = $this->getChoiceElementType($typeSuffix, $value);
            if ($targetType !== null) {
                return $this->denormalizer->denormalize($value, $targetType, $format, $context);
            }
        }

        return $this->denormalizeBasicValue($value, $format, $context);
    }

    /**
     * Get the target type for a choice element based on its suffix and value
     */
    private function getChoiceElementType(string $typeSuffix, mixed $value): ?string
    {
        // Map FHIR type suffixes to PHP types
        $typeMap = [
            'String'       => 'string',
            'Integer'      => 'int',
            'Boolean'      => 'bool',
            'Decimal'      => 'float',
            'Date'         => 'string',
            'DateTime'     => 'string',
            'Time'         => 'string',
            'Code'         => 'string',
            'Uri'          => 'string',
            'Url'          => 'string',
            'Canonical'    => 'string',
            'Base64Binary' => 'string',
            'Instant'      => 'string',
            'Oid'          => 'string',
            'Id'           => 'string',
            'Uuid'         => 'string',
            'Markdown'     => 'string',
            'Xhtml'        => 'string',
        ];

        if (isset($typeMap[$typeSuffix])) {
            return $typeMap[$typeSuffix];
        }

        // For complex types (like CodeableConcept), return null to use basic handling
        return null;
    }

    /**
     * Check if a value is a primitive with extensions
     */
    private function isPrimitiveWithExtensions(mixed $value, string $propertyName): bool
    {
        if (!is_object($value)) {
            return false;
        }

        return $this->metadataExtractor->isPrimitiveType($value);
    }

    /**
     * Normalize a primitive value with extensions
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    private function normalizePrimitiveWithExtensions(mixed $value, ?string $format, array $context): ?array
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
        if ($reflection->hasProperty('extension')) {
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
     * Normalize basic values (fallback when no normalizer is injected)
     *
     * @param array<string, mixed> $context
     */
    private function normalizeBasicValue(mixed $value, ?string $format, array $context): mixed
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
     * Denormalize basic values (fallback when no denormalizer is injected)
     *
     * @param array<string, mixed> $context
     */
    private function denormalizeBasicValue(mixed $value, ?string $format, array $context): mixed
    {
        // For basic denormalization, just return the value as-is
        return $value;
    }

    /**
     * Normalize complex type for JSON format
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForJSON(object $object, array $context): array
    {
        $data = [];

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value        = $property->getValue($object);

            // Skip null values according to FHIR JSON rules
            if ($value === null) {
                continue;
            }

            // Skip empty arrays according to FHIR JSON rules
            if (is_array($value) && empty($value)) {
                continue;
            }

            // Handle choice elements (value[x] pattern)
            if ($this->isChoiceElement($propertyName)) {
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$propertyName] = $normalizedValue;
                }
            } else {
                // Handle extensions with underscore notation for primitives
                if ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                    $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue['value'];
                        if (isset($normalizedValue['extensions'])) {
                            $data['_' . $propertyName] = $normalizedValue['extensions'];
                        }
                    }
                } else {
                    // Use the injected normalizer if available, otherwise handle basic types
                    if ($this->normalizer !== null) {
                        $normalizedValue = $this->normalizer->normalize($value, 'json', $context);
                    } else {
                        $normalizedValue = $this->normalizeBasicValue($value, 'json', $context);
                    }

                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Normalize complex type for XML format
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, array $context): array
    {
        $data = [];

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value        = $property->getValue($object);

            // Skip null values according to FHIR XML rules
            if ($value === null) {
                continue;
            }

            // Skip empty arrays according to FHIR XML rules
            if (is_array($value) && empty($value)) {
                continue;
            }

            // Handle choice elements (value[x] pattern) - same as JSON
            if ($this->isChoiceElement($propertyName)) {
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$propertyName] = $normalizedValue;
                }
            } else {
                // Handle primitive extensions for XML (no underscore notation)
                if ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                    $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue;
                    }
                } else {
                    // Use the injected normalizer if available, otherwise handle basic types
                    if ($this->normalizer !== null) {
                        $normalizedValue = $this->normalizer->normalize($value, 'xml', $context);
                    } else {
                        $normalizedValue = $this->normalizeBasicValue($value, 'xml', $context);
                    }

                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get the type of a property from its type hint
     */
    private function getPropertyType(\ReflectionProperty $property): ?string
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
    private function isBuiltinType(string $type): bool
    {
        return in_array($type, ['array', 'string', 'int', 'bool', 'float', 'null', 'mixed', 'object', 'callable', 'iterable'], true);
    }

    /**
     * Unwrap a FHIR XML value encoded as ['@value' => '...', '#' => ''] by Symfony's XmlEncoder.
     * XmlEncoder always adds a '#' key for empty text content alongside XML attributes.
     * Returns the scalar value when the array contains @value and optionally '#', otherwise returns as-is.
     * When the target property type is 'array' and the unwrapped value is not an array, wraps it in [].
     */
    private function unwrapXmlValue(mixed $value, ?string $propertyType): mixed
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

        return $value;
    }
}
