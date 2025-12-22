<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Ardenexal\FHIRTools\Attributes\FhirResource;
use Ardenexal\FHIRTools\Exception\FHIRSerializationException;

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

        // Create or extract FHIR serialization context
        $fhirContext = FHIRSerializationContext::fromSymfonyContext($context);

        // If format is specified as parameter, override the context format
        if ($format !== null && $format !== $fhirContext->format) {
            $fhirContext = $fhirContext->withFormat($format);
        }

        // Create debug info if enabled
        $debugInfo = null;
        if ($fhirContext->enableDebugInfo) {
            $debugInfo = FHIRSerializationDebugInfo::forNormalization(
                $fhirContext->format,
                null,
                get_class($object),
                self::class,
                $context,
            );
        }

        try {
            // Handle XML format
            if ($fhirContext->isXmlFormat()) {
                $result = $this->normalizeForXML($object, $resourceType, $fhirContext, $context);
            } else {
                // Handle JSON format (default)
                $result = $this->normalizeForJSON($object, $resourceType, $fhirContext, $context);
            }

            // Complete debug info if enabled
            if ($debugInfo !== null) {
                $debugInfo = $debugInfo->completed();
                // Store debug info in context for potential use by calling code
                $context['fhir_debug_info'] = $debugInfo;
            }

            return $result;
        } catch (\Throwable $e) {
            if ($debugInfo !== null) {
                $debugInfo = $debugInfo->completed()->withWarning("Exception: {$e->getMessage()}");
            }

            // Re-throw FHIR serialization exceptions and Symfony serializer exceptions as-is
            if ($e instanceof FHIRSerializationException || $e instanceof InvalidArgumentException) {
                throw $e;
            }

            // Only wrap unexpected exceptions
            throw FHIRSerializationException::formatError($fhirContext->format, $e->getMessage(), ['object_type' => get_class($object), 'resource_type' => $resourceType]);
        }
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
        // Create or extract FHIR serialization context
        $fhirContext = FHIRSerializationContext::fromSymfonyContext($context);

        // If format is specified as parameter, override the context format
        if ($format !== null && $format !== $fhirContext->format) {
            $fhirContext = $fhirContext->withFormat($format);
        }

        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        // Create debug info if enabled
        $debugInfo = null;
        if ($fhirContext->enableDebugInfo) {
            $debugInfo = FHIRSerializationDebugInfo::forDenormalization(
                $fhirContext->format,
                null,
                $type,
                self::class,
                $context,
            );
        }

        try {
            // Handle XML format
            if ($fhirContext->isXmlFormat()) {
                $result = $this->denormalizeFromXML($data, $type, $fhirContext, $context);
            } else {
                // Handle JSON format (default)
                $result = $this->denormalizeFromJSON($data, $type, $fhirContext, $context);
            }

            // Complete debug info if enabled
            if ($debugInfo !== null) {
                $debugInfo = $debugInfo->completed();
                // Store debug info in context for potential use by calling code
                $context['fhir_debug_info'] = $debugInfo;
            }

            return $result;
        } catch (\Throwable $e) {
            if ($debugInfo !== null) {
                $debugInfo = $debugInfo->completed()->withWarning("Exception: {$e->getMessage()}");
            }

            // Re-throw FHIR serialization exceptions and Symfony serializer exceptions as-is
            if ($e instanceof FHIRSerializationException || $e instanceof NotNormalizableValueException) {
                throw $e;
            }

            // Only wrap unexpected exceptions
            throw FHIRSerializationException::formatError($fhirContext->format, $e->getMessage(), ['target_type' => $type, 'data_keys' => array_keys($data)]);
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
    private function normalizePrimitiveWithExtensions(mixed $value, FHIRSerializationContext $fhirContext, array $context): ?array
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
        if ($reflection->hasProperty('extension') && $fhirContext->includeExtensions) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions        = $extensionProperty->getValue($value);
            if ($extensions !== null && !empty($extensions)) {
                if ($this->normalizer !== null) {
                    $result['extensions'] = $this->normalizer->normalize($extensions, $fhirContext->format, $context);
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
    private function denormalizePrimitiveWithExtensions(mixed $value, mixed $extensions, FHIRSerializationContext $fhirContext, array $context): mixed
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
    private function normalizeBasicValue(mixed $value, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $normalizedItem = $this->normalizeBasicValue($item, $fhirContext, $context);
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
                    $result[$property->getName()] = $this->normalizeBasicValue($propertyValue, $fhirContext, $context);
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
    private function denormalizeBasicValue(mixed $value, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        // For basic denormalization, just return the value as-is
        return $value;
    }

    /**
     * Check if a value should be omitted according to FHIR rules and context configuration
     */
    private function shouldOmitValue(mixed $value, FHIRSerializationContext $context): bool
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
     * Normalize array with potential sparse extensions
     *
     * @param array<mixed>         $array
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    private function normalizeArrayWithExtensions(array $array, string $propertyName, FHIRSerializationContext $fhirContext, array $context): ?array
    {
        if (empty($array)) {
            return null;
        }

        $normalizedValues = [];
        $extensions       = [];
        $hasExtensions    = false;

        foreach ($array as $index => $item) {
            if ($this->shouldOmitValue($item, $fhirContext)) {
                continue;
            }

            // Check if item has extensions
            if (is_object($item) && $this->metadataExtractor->isPrimitiveType($item)) {
                $primitiveResult = $this->normalizePrimitiveWithExtensions($item, $fhirContext, $context);
                if ($primitiveResult !== null) {
                    $normalizedValues[$index] = $primitiveResult['value'];
                    if (isset($primitiveResult['extensions']) && $fhirContext->includeExtensions) {
                        $extensions[$index] = $primitiveResult['extensions'];
                        $hasExtensions      = true;
                    } else {
                        $extensions[$index] = null;
                    }
                }
            } else {
                // Regular normalization
                if ($this->normalizer !== null) {
                    $normalizedItem = $this->normalizer->normalize($item, $fhirContext->format, $context);
                } else {
                    $normalizedItem = $this->normalizeBasicValue($item, $fhirContext, $context);
                }

                if ($normalizedItem !== null && !$this->shouldOmitValue($normalizedItem, $fhirContext)) {
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
    private function handleUnknownProperty(string $propertyName, mixed $value, string $policy, object $object, ?string $elementPath = null): void
    {
        switch ($policy) {
            case FHIRSerializationContext::UNKNOWN_POLICY_ERROR:
                throw FHIRSerializationException::unknownElementError($propertyName, $policy, $elementPath, ['property_value' => $value]);

            case FHIRSerializationContext::UNKNOWN_POLICY_PRESERVE:
                // Try to set the property dynamically if possible
                if (property_exists($object, $propertyName)) {
                    $object->{$propertyName} = $value;
                }
                // Could also store in a special "unknown properties" collection
                break;

            case FHIRSerializationContext::UNKNOWN_POLICY_IGNORE:
            default:
                // Do nothing - ignore the unknown property
                break;
        }
    }

    /**
     * Normalize resource for JSON format
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForJSON(object $object, string $resourceType, FHIRSerializationContext $fhirContext, array $context): array
    {
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
            if ($this->shouldOmitValue($value, $fhirContext)) {
                continue;
            }

            // Handle arrays with potential sparse extensions
            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayWithExtensions($value, $propertyName, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$propertyName] = $normalizedArray['values'];
                    if (isset($normalizedArray['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $propertyName] = $normalizedArray['extensions'];
                    }
                }
            } elseif ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                // Handle extensions with underscore notation for primitives
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext, $context);
                if ($normalizedValue !== null) {
                    $data[$propertyName] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $propertyName] = $normalizedValue['extensions'];
                    }
                }
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
                if ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, $fhirContext, $context);
                }

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$propertyName] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize resource for XML format
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, string $resourceType, FHIRSerializationContext $fhirContext, array $context): array
    {
        $data = [];

        // Add FHIR namespace declaration if enabled
        if ($fhirContext->enableXmlNamespaces) {
            $data['@xmlns'] = 'http://hl7.org/fhir';
        }

        // Add resourceType as XML element name (handled by parent serializer)
        $data['@resourceType'] = $resourceType;

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip resourceType as it's handled as element name
            if ($propertyName === 'resourceType') {
                continue;
            }

            $value = $property->getValue($object);

            // Apply FHIR XML omission rules (similar to JSON but no underscore notation)
            if ($this->shouldOmitValue($value, $fhirContext)) {
                continue;
            }

            // Handle arrays
            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayForXML($value, $propertyName, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$propertyName] = $normalizedArray;
                }
            } elseif ($this->isPrimitiveWithExtensions($value, $propertyName)) {
                // Handle primitive extensions for XML (as attributes and child elements)
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext, $context);
                if ($normalizedValue !== null) {
                    $data[$propertyName] = $normalizedValue;
                }
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
                if ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, $fhirContext, $context);
                }

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$propertyName] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize array for XML format
     *
     * @param array<mixed>         $array
     * @param array<string, mixed> $context
     *
     * @return array<mixed>|null
     */
    private function normalizeArrayForXML(array $array, string $propertyName, FHIRSerializationContext $fhirContext, array $context): ?array
    {
        if (empty($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $item) {
            if ($this->shouldOmitValue($item, $fhirContext)) {
                continue;
            }

            if ($this->normalizer !== null) {
                $normalizedItem = $this->normalizer->normalize($item, $fhirContext->format, $context);
            } else {
                $normalizedItem = $this->normalizeBasicValue($item, $fhirContext, $context);
            }

            if ($normalizedItem !== null) {
                $result[] = $normalizedItem;
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Denormalize from JSON format
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromJSON(array $data, string $type, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        // Validate that resourceType is present
        if (!isset($data['resourceType'])) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('Missing required resourceType field');
            } else {
                throw new NotNormalizableValueException('Missing required resourceType field');
            }
        }

        $resourceType = $data['resourceType'];
        if (!is_string($resourceType)) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('resourceType must be a string');
            } else {
                throw new NotNormalizableValueException('resourceType must be a string');
            }
        }

        if (empty($resourceType)) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('resourceType cannot be empty');
            } else {
                throw new NotNormalizableValueException('resourceType cannot be empty');
            }
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

            // Get unknown property policy from FHIR context
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            // Set properties from the data
            foreach ($data as $propertyName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($propertyName, '_')) {
                    continue;
                }

                // Skip XML-specific properties
                if (str_starts_with($propertyName, '@')) {
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
                            $fhirContext,
                            $context,
                        );
                    } else {
                        // Use the injected denormalizer if available
                        if ($this->denormalizer !== null) {
                            $propertyType = $this->getPropertyType($property);
                            if ($propertyType !== null) {
                                $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'json', $context);
                            } else {
                                $denormalizedValue = $value;
                            }
                        } else {
                            $denormalizedValue = $this->denormalizeBasicValue($value, $fhirContext, $context);
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                } else {
                    // Handle unknown properties according to policy
                    $this->handleUnknownProperty($propertyName, $value, $unknownPropertyPolicy, $object, $propertyName);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Denormalize from XML format
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromXML(array $data, string $type, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        // Extract resourceType from XML data (could be in @resourceType or element name)
        $resourceType = $data['@resourceType'] ?? $context['xml_element_name'] ?? null;

        if ($resourceType === null) {
            throw new NotNormalizableValueException('Missing required resourceType in XML data');
        }

        if (!is_string($resourceType)) {
            throw new NotNormalizableValueException('resourceType must be a string');
        }

        if (empty($resourceType)) {
            throw new NotNormalizableValueException('resourceType cannot be empty');
        }

        // Create data array for type resolver
        $resolverData = ['resourceType' => $resourceType];

        // Use type resolver to get the correct class for the resourceType
        $resolvedType = $this->typeResolver->resolveResourceType($resolverData);
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

            // Get unknown property policy from FHIR context
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            // Set properties from the data
            foreach ($data as $propertyName => $value) {
                // Skip XML-specific properties
                if (str_starts_with($propertyName, '@')) {
                    continue;
                }

                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);

                    // Use the injected denormalizer if available
                    if ($this->denormalizer !== null) {
                        $propertyType = $this->getPropertyType($property);
                        if ($propertyType !== null) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'xml', $context);
                        } else {
                            $denormalizedValue = $value;
                        }
                    } else {
                        $denormalizedValue = $this->denormalizeBasicValue($value, $fhirContext, $context);
                    }

                    $property->setValue($object, $denormalizedValue);
                } else {
                    // Handle unknown properties according to policy
                    $this->handleUnknownProperty($propertyName, $value, $unknownPropertyPolicy, $object, $propertyName);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
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
