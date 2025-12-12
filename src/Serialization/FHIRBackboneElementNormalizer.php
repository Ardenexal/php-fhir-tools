<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Ardenexal\FHIRTools\Attributes\FHIRBackboneElement;

/**
 * Normalizer for FHIR backbone elements within resources.
 *
 * This normalizer handles serialization and deserialization of FHIR backbone elements
 * following the official FHIR JSON specification. It supports backbone element
 * extensions and modifierExtensions, nested backbone element structures, maintains
 * parent-child relationships during serialization, and handles backbone element
 * metadata.
 *
 * @author Kiro AI Assistant
 */
class FHIRBackboneElementNormalizer implements FHIRNormalizerInterface
{
    public function __construct(
        private readonly FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly ?NormalizerInterface $normalizer = null,
        private readonly ?DenormalizerInterface $denormalizer = null
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Expected object, got ' . gettype($object));
        }

        if (!$this->metadataExtractor->isBackboneElement($object)) {
            throw new InvalidArgumentException('Object is not a FHIR backbone element');
        }

        $data = [];

        // Normalize all properties of the backbone element
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

            // Handle extensions and modifierExtensions specially
            if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                $normalizedValue = $this->normalizeExtensions($value, $format, $context);
                if ($normalizedValue !== null && !empty($normalizedValue)) {
                    $data[$propertyName] = $normalizedValue;
                }
            } else {
                // Handle primitive extensions with underscore notation
                if ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                    $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $format, $context);
                    if ($normalizedValue !== null) {
                        $data[$propertyName] = $normalizedValue['value'];
                        if (isset($normalizedValue['extensions'])) {
                            $data['_' . $propertyName] = $normalizedValue['extensions'];
                        }
                    }
                } else {
                    // Handle nested backbone elements and other complex types
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

        return $this->metadataExtractor->isBackboneElement($data);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        try {
            $reflection = new \ReflectionClass($type);

            // Use constructor if available to properly initialize properties
            $constructor = $reflection->getConstructor();
            if ($constructor !== null) {
                // Get constructor parameters and create with defaults
                $params = $constructor->getParameters();
                $args   = [];
                foreach ($params as $param) {
                    $args[] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                }
                $object = $reflection->newInstanceArgs($args);
            } else {
                $object = $reflection->newInstanceWithoutConstructor();
            }

            // Set properties from the data while maintaining parent-child relationships
            foreach ($data as $propertyName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($propertyName, '_')) {
                    continue;
                }

                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);

                    // Handle extensions and modifierExtensions specially
                    if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                        $denormalizedValue = $this->denormalizeExtensions($value, $format, $context);
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
                            // Handle nested backbone elements and other complex types
                            if ($this->denormalizer !== null) {
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, $format, $context);
                                } else {
                                    $denormalizedValue = $value;
                                }
                            } else {
                                // Without a denormalizer, we can only handle scalar values properly
                                // For complex objects, we'll have to skip them or return the raw data
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null && !$this->isScalarType($propertyType)) {
                                    // Skip complex types when no denormalizer is available
                                    $denormalizedValue = null;
                                } else {
                                    $denormalizedValue = $this->denormalizeBasicValue($value, $format, $context);
                                }
                            }
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
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

        // Check if the type is a FHIR backbone element class
        try {
            $reflection = new \ReflectionClass($type);
            $attributes = $reflection->getAttributes(FHIRBackboneElement::class);

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
        // This normalizer supports any class with the FHIRBackboneElement attribute
        return ['object' => true];
    }

    /**
     * Normalize extensions array
     *
     * @param array<string, mixed> $context
     */
    private function normalizeExtensions(mixed $extensions, ?string $format, array $context): ?array
    {
        if (!is_array($extensions) || empty($extensions)) {
            return null;
        }

        $result = [];
        foreach ($extensions as $extension) {
            if ($this->normalizer !== null) {
                $normalizedExtension = $this->normalizer->normalize($extension, $format, $context);
            } else {
                $normalizedExtension = $this->normalizeBasicValue($extension, $format, $context);
            }

            if ($normalizedExtension !== null) {
                $result[] = $normalizedExtension;
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Denormalize extensions array
     *
     * @param array<string, mixed> $context
     */
    private function denormalizeExtensions(mixed $extensions, ?string $format, array $context): ?array
    {
        if (!is_array($extensions) || empty($extensions)) {
            return null;
        }

        $result = [];
        foreach ($extensions as $extension) {
            if ($this->denormalizer !== null && is_array($extension)) {
                // Try to denormalize as Extension object - simplified for now
                $result[] = $extension;
            } else {
                $result[] = $this->denormalizeBasicValue($extension, $format, $context);
            }
        }

        return empty($result) ? null : $result;
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
        // For basic denormalization, if it's an array representing an object,
        // we can't properly reconstruct it without type information
        // So just return the value as-is for now
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

    /**
     * Check if a type is a scalar type
     */
    private function isScalarType(string $type): bool
    {
        return in_array($type, ['string', 'int', 'float', 'bool', 'array'], true);
    }
}
