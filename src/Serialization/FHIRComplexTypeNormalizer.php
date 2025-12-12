<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Ardenexal\FHIRTools\Attributes\FHIRComplexType;

/**
 * Normalizer for FHIR complex type classes (Address, HumanName, etc.).
 *
 * This normalizer handles serialization and deserialization of FHIR complex types
 * following the official FHIR JSON specification. It supports nested object
 * serialization, choice element (value[x]) patterns, complex type extensions,
 * and polymorphic complex type resolution.
 *
 * @author Kiro AI Assistant
 */
class FHIRComplexTypeNormalizer implements FHIRNormalizerInterface
{
    public function __construct(
        private readonly FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        private readonly ?NormalizerInterface $normalizer = null,
        private readonly ?DenormalizerInterface $denormalizer = null
    ) {
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

        $data = [];

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();
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
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, $format, $context);
                if ($normalizedValue !== null) {
                    $data[$propertyName] = $normalizedValue;
                }
            } else {
                // Handle extensions with underscore notation for primitives
                if ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                    $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $format, $context);
                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue['value'];
                        if (isset($normalizedValue['extensions'])) {
                            $data['_' . $propertyName] = $normalizedValue['extensions'];
                        }
                    }
                } else {
                    // Use the injected normalizer if available, otherwise handle basic types
                    if ($this->normalizer !== null) {
                        $normalizedValue = $this->normalizer->normalize($value, $format, $context);
                    } else {
                        $normalizedValue = $this->normalizeBasicValue($value, $format, $context);
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

        // Validate that the resolved type matches the expected type
        if ($resolvedType !== $type && !is_subclass_of($resolvedType, $type)) {
            throw new NotNormalizableValueException(sprintf('Resolved type "%s" is not compatible with expected type "%s"', $resolvedType, $type));
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
                        // Handle primitive extensions
                        $extensionKey = '_' . $propertyName;
                        if (isset($data[$extensionKey])) {
                            $denormalizedValue = $this->denormalizePrimitiveWithExtensions(
                                $value,
                                $data[$extensionKey],
                                $format,
                                $context,
                            );
                        } else {
                            // Use the injected denormalizer if available
                            if ($this->denormalizer !== null) {
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, $format, $context);
                                } else {
                                    $denormalizedValue = $value;
                                }
                            } else {
                                $denormalizedValue = $this->denormalizeBasicValue($value, $format, $context);
                            }
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
            $valueProperty   = $reflection->getProperty('value');
            $result['value'] = $valueProperty->getValue($value);
        }

        // Look for extension property
        if ($reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions        = $extensionProperty->getValue($value);
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
     * Denormalize a primitive value with extensions
     *
     * @param array<string, mixed> $context
     */
    private function denormalizePrimitiveWithExtensions(mixed $value, mixed $extensions, ?string $format, array $context): mixed
    {
        // For now, return the basic value - full primitive handling will be implemented
        // in the FHIRPrimitiveTypeNormalizer
        return $value;
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
}
