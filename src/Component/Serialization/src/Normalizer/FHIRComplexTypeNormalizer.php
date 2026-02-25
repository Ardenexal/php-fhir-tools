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
class FHIRComplexTypeNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null
    ) {
        parent::__construct($metadataExtractor, $normalizer, $denormalizer);
    }

    /**
     * {@inheritDoc}
     *
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

            // Get property metadata for choice element mapping
            $metaMap = $this->getPropertyMetadataMap($object);

            // Set properties from the data
            foreach ($data as $elementName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($elementName, '_')) {
                    continue;
                }

                // First, check if this is a choice element variant (e.g., 'valueQuantity' -> 'value')
                $choiceMapping = $this->findChoicePropertyByKey($metaMap, $elementName);
                if ($choiceMapping !== null) {
                    [$propertyName, $phpType] = $choiceMapping;

                    if ($reflection->hasProperty($propertyName)) {
                        $property = $reflection->getProperty($propertyName);

                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, $format, $context);
                        } else {
                            $denormalizedValue = $format === 'xml' ? $this->unwrapXmlValue($value, $phpType) : $value;
                        }

                        $property->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                // Standard property mapping
                if ($reflection->hasProperty($elementName)) {
                    $property = $reflection->getProperty($elementName);

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
     * Check if a property name represents a choice element (value[x] pattern).
     */
    private function isChoiceElement(string $propertyName): bool
    {
        // Choice elements typically start with 'value' followed by a type suffix
        return str_starts_with($propertyName, 'value') && strlen($propertyName) > 5;
    }

    /**
     * Normalize a choice element with proper type suffix handling.
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
     * Denormalize a choice element with proper type handling.
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
     * Get the target type for a choice element based on its suffix and value.
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
     * Normalize complex type for JSON format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForJSON(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            // Skip null values according to FHIR JSON rules
            if ($value === null) {
                continue;
            }

            // Skip empty arrays according to FHIR JSON rules
            if (is_array($value) && empty($value)) {
                continue;
            }

            $meta    = array_key_exists($propertyName, $metaMap) ? $metaMap[$propertyName] : null;
            $jsonKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            // Handle choice elements: metadata-driven (generated classes) or legacy heuristic
            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
                // Metadata-driven: resolve concrete JSON key and kind from variant map
                [$resolvedKind, $resolvedKey] = $this->resolveChoiceVariant($value, $meta->variants);
                if ($resolvedKey !== '') {
                    $jsonKey = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if (isset($normalizedValue['extensions'])) {
                                $data['_' . $jsonKey] = $normalizedValue['extensions'];
                            }
                        }
                    } else {
                        $normalizedValue = $this->normalizer !== null
                            ? $this->normalizer->normalize($value, 'json', $context)
                            : $this->normalizeBasicValue($value, 'json', $context);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            } elseif ($isChoice) {
                // Legacy heuristic fallback for non-generated classes
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue;
                }
                continue;
            }

            // Handle extensions with underscore notation for primitives
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions'])) {
                        $data['_' . $jsonKey] = $normalizedValue['extensions'];
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
                    $data[$jsonKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize complex type for XML format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            // Skip null values according to FHIR XML rules
            if ($value === null) {
                continue;
            }

            // Skip empty arrays according to FHIR XML rules
            if (is_array($value) && empty($value)) {
                continue;
            }

            $meta   = array_key_exists($propertyName, $metaMap) ? $metaMap[$propertyName] : null;
            $xmlKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            // Handle choice elements: metadata-driven (generated classes) or legacy heuristic
            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
                // Metadata-driven: resolve concrete XML key and kind from variant map
                [$resolvedKind, $resolvedKey] = $this->resolveChoiceVariant($value, $meta->variants);
                if ($resolvedKey !== '') {
                    $xmlKey = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
                        if ($normalizedValue !== null) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    } else {
                        $normalizedValue = $this->normalizer !== null
                            ? $this->normalizer->normalize($value, 'xml', $context)
                            : $this->normalizeBasicValue($value, 'xml', $context);
                        if ($normalizedValue !== null) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            } elseif ($isChoice) {
                // Legacy heuristic fallback for non-generated classes
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
                continue;
            }

            // Handle primitive extensions for XML (no underscore notation)
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
                if ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, 'xml', $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, 'xml', $context);
                }

                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }
}
