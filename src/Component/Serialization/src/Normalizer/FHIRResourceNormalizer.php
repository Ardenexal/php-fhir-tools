<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizer for FHIR resource classes with resourceType handling.
 *
 * This normalizer handles serialization and deserialization of FHIR resources
 * following the official FHIR JSON specification. It supports resource-level
 * extensions, metadata, and discriminator map support for polymorphic resources.
 *
 * @author Ardenexal
 */
class FHIRResourceNormalizer extends AbstractFHIRNormalizer
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

        // JSON requires a resourceType discriminator field.
        // XML does not include it — the root element name is the resource type and
        // is already resolved into the $type class parameter before we are called.
        if ($format !== 'xml' && !isset($data['resourceType'])) {
            return false;
        }

        // Check if the type is a FHIR resource class
        try {
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
     * Normalize array with potential sparse extensions.
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
                $primitiveResult = $this->normalizePrimitiveWithExtensions($item, $fhirContext->format, $context, $fhirContext->includeExtensions);
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
                // Regular normalization — skip the normalizer chain for raw PHP arrays
                // (they are already plain data, not FHIR objects)
                if (is_array($item)) {
                    $normalizedItem = $item;
                } elseif ($this->normalizer !== null) {
                    $normalizedItem = $this->normalizer->normalize($item, $fhirContext->format, $context);
                } else {
                    $normalizedItem = $this->normalizeBasicValue($item, $fhirContext->format, $context);
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
     * Handle unknown properties according to the configured policy.
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
     * Normalize resource for JSON format.
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
        $metaMap    = $this->getPropertyMetadataMap($object);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip resourceType as we already handled it
            if ($propertyName === 'resourceType') {
                continue;
            }

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            // Apply FHIR JSON omission rules
            if ($this->shouldOmitValue($value, $fhirContext)) {
                continue;
            }

            $meta    = array_key_exists($propertyName, $metaMap) ? $metaMap[$propertyName] : null;
            $jsonKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            // Resolve choice element to correct concrete JSON key
            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey, $resolvedFhirType] = $choiceMatch;
                    $jsonKey                                         = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext->format, $context, $fhirContext->includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if (isset($normalizedValue['extensions']) && $fhirContext->includeExtensions) {
                                $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                            }
                        }
                    } else {
                        if ($this->normalizer !== null) {
                            $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                        } else {
                            $normalizedValue = $this->normalizeBasicValue($value, $fhirContext->format, $context);
                        }
                        if (($resolvedFhirType === 'decimal' || $resolvedFhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_string($normalizedValue) && is_numeric($normalizedValue)) {
                            $normalizedValue = (float) $normalizedValue;
                        }
                        if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                            $data[$jsonKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            }

            // Handle arrays with potential sparse extensions
            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayWithExtensions($value, $jsonKey, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$jsonKey] = $normalizedArray['values'];
                    if (isset($normalizedArray['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $jsonKey] = $normalizedArray['extensions'];
                    }
                }
            } elseif ($this->isPrimitiveWithExtensions($value)) {
                // Handle extensions with underscore notation for primitives
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext->format, $context, $fhirContext->includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                    }
                }
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
                if ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, $fhirContext->format, $context);
                }

                $normalizedValue = $this->castNumericScalarForJson($normalizedValue, $meta);

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$jsonKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize resource for XML format.
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

        // Note: resourceType is set as XML root element name by FHIRSerializationService
        // via XmlEncoder::ROOT_NODE_NAME, so we don't add it to the data array

        // Normalize all properties of the object
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $metaMap    = $this->getPropertyMetadataMap($object);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // Skip resourceType as it's handled as element name
            if ($propertyName === 'resourceType') {
                continue;
            }

            // Skip uninitialized typed properties (created via newInstanceWithoutConstructor)
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            // Apply FHIR XML omission rules (similar to JSON but no underscore notation)
            if ($this->shouldOmitValue($value, $fhirContext)) {
                continue;
            }

            $meta    = array_key_exists($propertyName, $metaMap) ? $metaMap[$propertyName] : null;
            $xmlKey  = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            // Resolve choice element to correct concrete XML element name
            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey] = $choiceMatch;
                    $xmlKey                       = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext->format, $context, $fhirContext->includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    } elseif (is_scalar($value)) {
                        // Scalar choice elements (bool, int, float, string) must emit as
                        // <element value="..."/> in FHIR XML. Build the @value array directly
                        // rather than delegating to the Symfony normalizer, which would let
                        // XmlEncoder apply its own bool→int or float→string casts.
                        $data[$xmlKey] = [
                            '@value' => is_bool($value)
                                ? ($value ? 'true' : 'false')
                                : (string) $value,
                        ];
                    } else {
                        if ($this->normalizer !== null) {
                            $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                        } else {
                            $normalizedValue = $this->normalizeBasicValue($value, $fhirContext->format, $context);
                        }
                        if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            }

            // Handle polymorphic resource properties in XML: each resource must be wrapped
            // with its resource type as the element name, e.g.
            //   <contained><Medication>...</Medication></contained>
            if ($meta !== null && $meta->propertyKind === 'resource') {
                if ($meta->isArray && is_array($value)) {
                    $wrappedItems = [];
                    foreach ($value as $item) {
                        if (!is_object($item)) {
                            continue;
                        }
                        $itemResourceType = $this->metadataExtractor->extractResourceType($item);
                        if ($itemResourceType === null) {
                            continue;
                        }
                        $normalizedItem = $this->normalizer !== null
                            ? $this->normalizer->normalize($item, $fhirContext->format, $context)
                            : $this->normalizeBasicValue($item, $fhirContext->format, $context);
                        if ($normalizedItem !== null) {
                            $wrappedItems[] = [$itemResourceType => $normalizedItem];
                        }
                    }
                    if (!empty($wrappedItems)) {
                        $data[$xmlKey] = $wrappedItems;
                    }
                } elseif (!$meta->isArray && is_object($value)) {
                    $itemResourceType = $this->metadataExtractor->extractResourceType($value);
                    if ($itemResourceType !== null) {
                        $normalizedValue = $this->normalizer !== null
                            ? $this->normalizer->normalize($value, $fhirContext->format, $context)
                            : $this->normalizeBasicValue($value, $fhirContext->format, $context);
                        if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                            $data[$xmlKey] = [$itemResourceType => $normalizedValue];
                        }
                    }
                }
                continue;
            }

            // Handle arrays
            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayForXML($value, $xmlKey, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$xmlKey] = $normalizedArray;
                }
            } elseif ($meta !== null && $meta->xmlSerializedName !== null && is_scalar($value)) {
                // xmlAttr properties: emit as XML attribute on the parent element
                $data[$meta->xmlSerializedName] = is_bool($value)
                    ? ($value ? 'true' : 'false')
                    : (string) $value;
            } elseif ($this->isPrimitiveWithExtensions($value)) {
                // Handle primitive extensions for XML (as attributes and child elements)
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, $fhirContext->format, $context, $fhirContext->includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } else {
                // FHIR XML: scalar values emit as child elements with @value attribute.
                // Booleans: 'true'/'false'; other scalars: cast to string.
                if (is_scalar($value)) {
                    $normalizedValue = ['@value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value];
                } elseif ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, $fhirContext->format, $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, $fhirContext->format, $context);
                }

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$xmlKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize array for XML format.
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
                $normalizedItem = $this->normalizeBasicValue($item, $fhirContext->format, $context);
            }

            if ($normalizedItem !== null) {
                $result[] = $normalizedItem;
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Denormalize from JSON format.
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
            }
            throw new NotNormalizableValueException('Missing required resourceType field');
        }

        $resourceType = $data['resourceType'];
        if (!is_string($resourceType)) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('resourceType must be a string');
            }
            throw new NotNormalizableValueException('resourceType must be a string');
        }

        if (empty($resourceType)) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('resourceType cannot be empty');
            }
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

            // Get property metadata for choice element mapping
            $metaMap = $this->getPropertyMetadataMap($object);

            // Get unknown property policy from FHIR context
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            // Set properties from the data
            foreach ($data as $elementName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($elementName, '_')) {
                    continue;
                }

                // Skip XML-specific properties
                if (str_starts_with($elementName, '@')) {
                    continue;
                }

                // First, check if this is a choice element variant (e.g., 'valueQuantity' -> 'value')
                $choiceMapping = $this->findChoicePropertyByKey($metaMap, $elementName);
                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    if ($reflection->hasProperty($propertyName)) {
                        $property = $reflection->getProperty($propertyName);

                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'json', $context);
                        } else {
                            $denormalizedValue = ($fhirType === 'decimal' || $fhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_numeric($value)
                                ? (string) $value
                                : $value;
                        }

                        $property->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                // Standard property mapping
                if ($reflection->hasProperty($elementName)) {
                    $property     = $reflection->getProperty($elementName);
                    $meta         = $metaMap[$elementName] ?? null;
                    $phpItemClass = $meta?->phpItemClass;

                    if ($phpItemClass !== null && $this->denormalizer !== null && is_array($value)) {
                        // Complex/backbone array property: denormalize each JSON array item to a typed object.
                        $denormalizedValue = [];
                        foreach ($value as $item) {
                            $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'json', $context);
                        }
                    } elseif ($this->denormalizer !== null && $meta !== null && $meta->propertyKind === 'primitive') {
                        // Always produce Primitive objects so that _property extension data
                        // can be attached to the instances in the second pass below.
                        $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $property, $reflection, $value, 'json', $context, $metaMap);
                    } elseif ($this->denormalizer !== null) {
                        $propertyType = $this->getPropertyType($property);
                        if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'json', $context);
                        } else {
                            $denormalizedValue = $value;
                        }
                    } else {
                        $denormalizedValue = $value;
                    }

                    $property->setValue($object, $denormalizedValue);
                } else {
                    // Handle unknown properties according to policy
                    $this->handleUnknownProperty($elementName, $value, $unknownPropertyPolicy, $object, $elementName);
                }
            }

            // Apply _property extension data to already-denormalized primitive properties.
            $this->applyPrimitiveExtensions($reflection, $object, $data, $metaMap, 'json', $context);

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Denormalize from XML format.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromXML(array $data, string $type, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        // For XML, the $type parameter is already the correct resolved resource class
        // (resolved from the XML root element name by FHIRSerializationService before we are called).
        // We do NOT look for a 'resourceType' key in the data array — it is not present in FHIR XML.
        $resolvedType = $type;

        try {
            /** @var class-string $resolvedType */
            $reflection = new \ReflectionClass($resolvedType);
            $object     = $reflection->newInstanceWithoutConstructor();

            // Get property metadata for choice element mapping
            $metaMap = $this->getPropertyMetadataMap($object);

            // Get unknown property policy from FHIR context
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            // Set properties from the data
            foreach ($data as $elementName => $value) {
                // Skip XML-specific keys: attributes (@xmlns), text nodes (#), comments (#comment)
                if (str_starts_with($elementName, '@') || str_starts_with($elementName, '#')) {
                    continue;
                }

                // First, check if this is a choice element variant (e.g., 'valueQuantity' -> 'value')
                $choiceMapping = $this->findChoicePropertyByKey($metaMap, $elementName);
                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    if ($reflection->hasProperty($propertyName)) {
                        $property = $reflection->getProperty($propertyName);

                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'xml', $context);
                        } else {
                            $rawValue = $this->unwrapXmlValue($value, $phpType);

                            // XML: cast to correct PHP scalar type so FHIRPath inferType() returns
                            // the right type ('decimal' for float, 'integer' for int, etc.)
                            if ($phpType === 'int') {
                                $denormalizedValue = (int) $rawValue;
                            } elseif ($phpType === 'float') {
                                $denormalizedValue = (float) $rawValue;
                            } elseif ($phpType === 'bool') {
                                $denormalizedValue = filter_var($rawValue, FILTER_VALIDATE_BOOLEAN);
                            } else {
                                $denormalizedValue = $rawValue;
                            }
                        }

                        $property->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                // Standard property mapping
                if ($reflection->hasProperty($elementName)) {
                    $property         = $reflection->getProperty($elementName);
                    $propertyType     = $this->getPropertyType($property);
                    $propertyMetadata = $metaMap[$elementName] ?? null;
                    $phpItemClass     = $propertyMetadata?->phpItemClass;

                    // Special handling for polymorphic resource properties (e.g., Bundle.entry.resource,
                    // DomainResource::$contained). In XML the actual resource type is determined by
                    // the nested element name, e.g. <contained><Medication>...</Medication></contained>.
                    if ($propertyMetadata !== null && $propertyMetadata->propertyKind === 'resource') {
                        if ($propertyMetadata->isArray) {
                            // Array of polymorphic resources (e.g. DomainResource::$contained).
                            // XmlEncoder decodes a single <contained> element as a non-list assoc
                            // array; multiple elements arrive as a list — normalise to a list first.
                            $items             = is_array($value) && !array_is_list($value) ? [$value] : (array) $value;
                            $denormalizedValue = [];

                            foreach ($items as $item) {
                                $resourceElementName = $this->extractResourceElementName($item);
                                if ($resourceElementName === null) {
                                    continue;
                                }

                                $resolvedClass = $this->typeResolver->resolveResourceType([
                                    'resourceType' => $resourceElementName,
                                ]);

                                if ($resolvedClass !== null && $this->denormalizer !== null && is_array($item)) {
                                    $denormalizedValue[] = $this->denormalizer->denormalize(
                                        $item[$resourceElementName],
                                        $resolvedClass,
                                        'xml',
                                        $context,
                                    );
                                }
                            }

                            $property->setValue($object, $denormalizedValue);
                            continue;
                        }

                        // Single polymorphic resource (non-array), e.g. Bundle.entry.resource.
                        $resourceElementName = $this->extractResourceElementName($value);
                        if ($resourceElementName !== null) {
                            $resolvedClass = $this->typeResolver->resolveResourceType([
                                'resourceType' => $resourceElementName,
                            ]);

                            if ($resolvedClass !== null && $this->denormalizer !== null) {
                                $denormalizedValue = $this->denormalizer->denormalize(
                                    $value[$resourceElementName],
                                    $resolvedClass,
                                    'xml',
                                    $context,
                                );
                                $property->setValue($object, $denormalizedValue);
                                continue;
                            }
                        }
                        // If we couldn't resolve, fall through to default handling
                    }

                    if ($propertyMetadata !== null
                        && ($propertyMetadata->propertyKind === 'extension' || $propertyMetadata->propertyKind === 'modifierExtension')
                        && $this->denormalizer !== null
                    ) {
                        // Extension/modifierExtension: phpItemClass is null in FHIR_PROPERTY_MAP for these.
                        // Unwrap XmlEncoder output (single non-list assoc array → wrap in list),
                        // then denormalize each item to a typed Extension object.
                        $items = $this->unwrapXmlValue($value, 'array');
                        if (is_array($items) && !array_is_list($items)) {
                            $items = [$items];
                        }
                        $denormalizedValue = $this->denormalizeExtensionArray((array) $items, 'xml', $context);
                    } elseif ($phpItemClass !== null && $this->denormalizer !== null) {
                        // Complex/backbone array property: denormalize each item to a typed object.
                        // unwrapXmlValue strips XmlEncoder meta-keys; the single-element case produces
                        // an associative array (not a list) — wrap it so we always iterate a list.
                        $items = $this->unwrapXmlValue($value, 'array');
                        if (is_array($items) && !array_is_list($items)) {
                            $items = [$items];
                        }
                        $denormalizedValue = [];
                        foreach ((array) $items as $item) {
                            $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'xml', $context);
                        }
                    } elseif ($this->denormalizer !== null && $propertyType !== null && !$this->isBuiltinType($propertyType)) {
                        $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'xml', $context);
                    } else {
                        // For built-in PHP types, unwrap the FHIR XML @value wrapper if present.
                        // Symfony XmlEncoder decodes <id value="example"/> as ['@value' => 'example'].
                        $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);

                        // Cast to correct PHP scalar type based on FhirProperty metadata
                        $scalarPhpType = $propertyMetadata?->phpType;
                        if ($scalarPhpType === 'int') {
                            $denormalizedValue = (int) $denormalizedValue;
                        } elseif ($scalarPhpType === 'float') {
                            $denormalizedValue = (float) $denormalizedValue;
                        } elseif ($scalarPhpType === 'bool') {
                            $denormalizedValue = filter_var($denormalizedValue, FILTER_VALIDATE_BOOLEAN);
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                } else {
                    // Handle unknown properties according to policy
                    $this->handleUnknownProperty($elementName, $value, $unknownPropertyPolicy, $object, $elementName);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
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
}
