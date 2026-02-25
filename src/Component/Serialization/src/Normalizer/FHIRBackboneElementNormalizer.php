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
            }

            // Handle primitive extensions with underscore notation
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions'])) {
                        $data['_' . $jsonKey] = $normalizedValue['extensions'];
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
}
