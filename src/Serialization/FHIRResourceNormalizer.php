<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Ardenexal\FHIRTools\Attributes\FhirResource;

/**
 * Normalizer for FHIR resource classes with resourceType handling.
 *
 * This normalizer handles serialization and deserialization of FHIR resources
 * following the official FHIR JSON specification. It supports resource-level
 * extensions, metadata, and discriminator map support for polymorphic resources.
 *
 * @author Kiro AI Assistant
 */
class FHIRResourceNormalizer implements FHIRNormalizerInterface
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

        if (!$this->metadataExtractor->isResource($object)) {
            throw new InvalidArgumentException('Object is not a FHIR resource');
        }

        $resourceType = $this->metadataExtractor->extractResourceType($object);
        if ($resourceType === null) {
            throw new InvalidArgumentException('Could not extract resource type from object');
        }

        $data = [];

        // Always include resourceType as the first field for FHIR JSON compliance
        $data['resourceType'] = $resourceType;

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip resourceType as we already handled it
            if ($propertyName === 'resourceType') {
                continue;
            }

            $value = $property->getValue($object);

            // Apply FHIR JSON omission rules
            if ($this->shouldOmitValue($value)) {
                continue;
            }

            // Handle arrays with potential sparse extensions
            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayWithExtensions($value, $propertyName, $format, $context);
                if ($normalizedArray !== null) {
                    $data[$propertyName] = $normalizedArray['values'];
                    if (isset($normalizedArray['extensions'])) {
                        $data['_' . $propertyName] = $normalizedArray['extensions'];
                    }
                }
            } elseif ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                // Handle extensions with underscore notation for primitives
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

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue)) {
                    $data[$propertyName] = $normalizedValue;
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

        return $this->metadataExtractor->isResource($data);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        // Validate that resourceType is present
        if (!isset($data['resourceType'])) {
            throw new NotNormalizableValueException('Missing required resourceType field');
        }

        $resourceType = $data['resourceType'];
        if (!is_string($resourceType)) {
            throw new NotNormalizableValueException('resourceType must be a string');
        }

        if (empty($resourceType)) {
            throw new NotNormalizableValueException('resourceType cannot be empty');
        }

        // Use type resolver to get the correct class for the resourceType
        $resolvedType = $this->typeResolver->resolveResourceType($data);
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

            // Get unknown property policy from context
            $unknownPropertyPolicy = $context['unknown_property_policy'] ?? 'ignore';

            // Set properties from the data
            foreach ($data as $propertyName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($propertyName, '_')) {
                    continue;
                }

                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);

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

                    $property->setValue($object, $denormalizedValue);
                } else {
                    // Handle unknown properties according to policy
                    $this->handleUnknownProperty($propertyName, $value, $unknownPropertyPolicy, $object);
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

        // Must have resourceType field
        if (!isset($data['resourceType'])) {
            return false;
        }

        // Check if the type is a FHIR resource class
        try {
            /** @var class-string $type */
            /** @var class-string $type */
            $reflection = new \ReflectionClass($type);
            $attributes = $reflection->getAttributes(FhirResource::class);

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
        // This normalizer supports any class with the FhirResource attribute
        return ['object' => true];
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
     * Check if a value should be omitted according to FHIR JSON rules
     */
    private function shouldOmitValue(mixed $value): bool
    {
        // Omit null values
        if ($value === null) {
            return true;
        }

        // Omit empty arrays
        if (is_array($value) && empty($value)) {
            return true;
        }

        // Omit empty strings (configurable)
        if (is_string($value) && $value === '') {
            return true;
        }

        return false;
    }

    /**
     * Normalize array with potential sparse extensions
     *
     * @param array<mixed>         $array
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    private function normalizeArrayWithExtensions(array $array, string $propertyName, ?string $format, array $context): ?array
    {
        if (empty($array)) {
            return null;
        }

        $normalizedValues = [];
        $extensions       = [];
        $hasExtensions    = false;

        foreach ($array as $index => $item) {
            if ($this->shouldOmitValue($item)) {
                continue;
            }

            // Check if item has extensions
            if (is_object($item) && $this->metadataExtractor->isPrimitiveType($item)) {
                $primitiveResult = $this->normalizePrimitiveWithExtensions($item, $format, $context);
                if ($primitiveResult !== null) {
                    $normalizedValues[$index] = $primitiveResult['value'];
                    if (isset($primitiveResult['extensions'])) {
                        $extensions[$index] = $primitiveResult['extensions'];
                        $hasExtensions      = true;
                    } else {
                        $extensions[$index] = null;
                    }
                }
            } else {
                // Regular normalization
                if ($this->normalizer !== null) {
                    $normalizedItem = $this->normalizer->normalize($item, $format, $context);
                } else {
                    $normalizedItem = $this->normalizeBasicValue($item, $format, $context);
                }

                if ($normalizedItem !== null && !$this->shouldOmitValue($normalizedItem)) {
                    $normalizedValues[$index] = $normalizedItem;
                    $extensions[$index]       = null;
                }
            }
        }

        if (empty($normalizedValues)) {
            return null;
        }

        $result = ['values' => array_values($normalizedValues)];

        // Only include extensions if there are actual extensions
        if ($hasExtensions) {
            // Create sparse extension array with same indices as values
            $sparseExtensions = [];
            $valueIndices     = array_keys($normalizedValues);
            foreach ($valueIndices as $originalIndex => $valueIndex) {
                $sparseExtensions[$originalIndex] = $extensions[$valueIndex] ?? null;
            }
            $result['extensions'] = array_values($sparseExtensions);
        }

        return $result;
    }

    /**
     * Handle unknown properties according to the configured policy
     */
    private function handleUnknownProperty(string $propertyName, mixed $value, string $policy, object $object): void
    {
        switch ($policy) {
            case 'error':
                throw new NotNormalizableValueException(sprintf('Unknown property "%s" encountered', $propertyName));

            case 'preserve':
                // Try to set the property dynamically if possible
                if (property_exists($object, $propertyName)) {
                    $object->{$propertyName} = $value;
                }
                // Could also store in a special "unknown properties" collection
                break;

            case 'ignore':
            default:
                // Do nothing - ignore the unknown property
                break;
        }
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
