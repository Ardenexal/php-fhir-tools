<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizer for FHIR backbone elements within resources.
 *
 * This normalizer handles serialization and deserialization of FHIR backbone elements
 * following the official FHIR JSON specification. It supports backbone element
 * extensions and modifierExtensions, nested backbone element structures, maintains
 * parent-child relationships during serialization, and handles backbone element
 * metadata.
 *
 * @author Ardenexal
 */
class FHIRBackboneElementNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
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

        if (!$this->metadataExtractor->isBackboneElement($object)) {
            throw new InvalidArgumentException('Object is not a FHIR backbone element');
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
            /** @var class-string $type */
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

            // Get property metadata for choice element mapping
            $metaMap = $this->getPropertyMetadataMap($object);

            // Set properties from the data while maintaining parent-child relationships
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

                    // Handle extensions and modifierExtensions specially
                    if ($elementName === 'extension' || $elementName === 'modifierExtension') {
                        $denormalizedValue = $this->denormalizeExtensions($value, $format, $context);
                    } else {
                        $meta = $metaMap[$elementName] ?? null;

                        // Special handling for polymorphic resource properties (e.g., Bundle.entry.resource)
                        // In XML, the actual resource type is determined by the nested element name
                        if ($meta !== null && $meta->propertyKind === 'resource' && $format === 'xml') {
                            $resourceElementName = $this->extractResourceElementName($value);
                            if ($resourceElementName !== null && $this->denormalizer !== null) {
                                $resolvedClass = $this->resolveResourceType($resourceElementName);
                                if ($resolvedClass !== null) {
                                    $denormalizedValue = $this->denormalizer->denormalize(
                                        $value[$resourceElementName],
                                        $resolvedClass,
                                        $format,
                                        $context,
                                    );
                                    $property->setValue($object, $denormalizedValue);
                                    continue;
                                }
                            }
                            // If we couldn't resolve, fall through to default handling
                        }

                        if ($this->denormalizer !== null) {
                            if ($meta !== null && $meta->propertyKind === 'primitive' && $format !== 'xml') {
                                // Always produce Primitive objects so that _property extension data
                                // can be attached to the instances in the second pass below.
                                $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $property, $reflection, $value, $format, $context, $metaMap);
                            } else {
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, $format, $context);
                                } else {
                                    // For XML, Symfony XmlEncoder wraps primitive values as ['@value' => '...', '#' => ''].
                                    // Unwrap before assigning to string/union-typed properties.
                                    if ($format === 'xml') {
                                        $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);
                                        // If unwrapping left an array (because the XML element had child elements,
                                        // e.g. an inline extension), extract just the scalar @value so it can be
                                        // assigned to union-typed properties like StringPrimitive|string|null.
                                        if (is_array($denormalizedValue) && isset($denormalizedValue['@value'])) {
                                            $denormalizedValue = $denormalizedValue['@value'];
                                        }
                                    } else {
                                        $denormalizedValue = $value;
                                    }
                                }
                            }
                        } else {
                            // Without a denormalizer, we can only handle scalar values properly
                            // For complex objects, we'll have to skip them or return the raw data
                            $propertyType = $this->getPropertyType($property);
                            if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                // Skip complex types when no denormalizer is available
                                $denormalizedValue = null;
                            } else {
                                $denormalizedValue = $this->denormalizeBasicValue($value, $format, $context);
                            }
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                }
            }

            // Apply _property extension data to already-denormalized primitive properties.
            $this->applyPrimitiveExtensions($reflection, $object, $data, $metaMap, $format, $context);

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
            /** @var class-string $type */
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
     * Normalize extensions array.
     *
     * @param array<string, mixed> $context
     *
     * @return list<mixed>|null
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

        return count($result) === 0 ? null : $result;
    }

    /**
     * Denormalize extensions array.
     *
     * @param array<string, mixed> $context
     *
     * @return list<mixed>|null
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

        return $result;
    }

    /**
     * Normalize backbone element for JSON format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForJSON(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the backbone element
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

            // Handle extensions and modifierExtensions specially
            if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                $normalizedValue = $this->normalizeExtensions($value, 'json', $context);
                if ($normalizedValue !== null && !empty($normalizedValue)) {
                    $data[$propertyName] = $normalizedValue;
                }
                continue;
            }

            // Handle choice elements via metadata
            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                [$resolvedKind, $resolvedKey] = $this->resolveChoiceVariant($value, $meta->variants);
                if ($resolvedKey !== '') {
                    $jsonKey = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if (isset($normalizedValue['extensions'])) {
                                $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
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
            }

            // Handle primitive extensions with underscore notation
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions'])) {
                        $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                    }
                }
            } else {
                // Handle nested backbone elements and other complex types
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
     * Normalize backbone element for XML format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the backbone element
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

            // Handle extensions and modifierExtensions specially
            if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                $normalizedValue = $this->normalizeExtensions($value, 'xml', $context);
                if ($normalizedValue !== null && !empty($normalizedValue)) {
                    $data[$propertyName] = $normalizedValue;
                }
                continue;
            }

            // Handle choice elements via metadata
            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
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
            }

            // Handle primitive extensions for XML (no underscore notation)
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } else {
                // Handle nested backbone elements and other complex types
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

    /**
     * Extract the resource element name from XML data for polymorphic resource properties.
     *
     * In FHIR XML, polymorphic resource properties (e.g., Bundle.entry.resource) contain
     * a nested element whose name indicates the resource type:
     * <resource><Patient>...</Patient></resource>
     *
     * @param mixed $value The XML data (already decoded to array by XmlEncoder)
     *
     * @return string|null The resource element name (e.g., 'Patient'), or null if not found
     */
    private function extractResourceElementName(mixed $value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        // Look for the first non-XML-metadata key (ignore @attributes, #text, #comment, etc.)
        foreach ($value as $key => $data) {
            if (!str_starts_with($key, '@') && !str_starts_with($key, '#')) {
                // This should be the resource element name
                return $key;
            }
        }

        return null;
    }

    /**
     * Resolve resource type name to fully qualified class name.
     *
     * @param string $resourceType The FHIR resource type (e.g., 'Patient', 'Bundle')
     *
     * @return string|null The fully qualified class name, or null if not found
     */
    private function resolveResourceType(string $resourceType): ?string
    {
        // Try each supported FHIR version
        foreach (['R4', 'R4B', 'R5'] as $version) {
            $candidate = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource\\{$resourceType}Resource";
            if (class_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
