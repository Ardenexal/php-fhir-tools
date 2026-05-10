<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * XML normalizer for FHIR resource classes.
 *
 * @author Ardenexal
 */
class FHIRResourceXmlNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        string $fhirVersion = 'R4',
        ?FHIRIGTypeRegistry $igTypeRegistry = null,
    ) {
        parent::__construct($metadataExtractor, $normalizer, $denormalizer, $fhirVersion, $igTypeRegistry);
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

        $fhirContext = FHIRSerializationContext::fromSymfonyContext($context);

        $debugInfo = null;
        if ($fhirContext->enableDebugInfo) {
            $debugInfo = FHIRSerializationDebugInfo::forNormalization('xml', null, get_class($object), self::class, $context);
        }

        try {
            $result = $this->normalizeForXML($object, $resourceType, $fhirContext, $context);

            if ($debugInfo !== null) {
                $context['fhir_debug_info'] = $debugInfo->completed();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($e instanceof FHIRSerializationException || $e instanceof InvalidArgumentException) {
                throw $e;
            }

            throw FHIRSerializationException::formatError('xml', $e->getMessage(), ['object_type' => get_class($object), 'resource_type' => $resourceType]);
        }
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

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
        $fhirContext = FHIRSerializationContext::fromSymfonyContext($context);

        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        $debugInfo = null;
        if ($fhirContext->enableDebugInfo) {
            $debugInfo = FHIRSerializationDebugInfo::forDenormalization('xml', null, $type, self::class, $context);
        }

        try {
            $result = $this->denormalizeFromXML($data, $type, $fhirContext, $context);

            if ($debugInfo !== null) {
                $context['fhir_debug_info'] = $debugInfo->completed();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($e instanceof FHIRSerializationException || $e instanceof NotNormalizableValueException) {
                throw $e;
            }

            throw FHIRSerializationException::formatError('xml', $e->getMessage(), ['target_type' => $type, 'data_keys' => array_keys($data)]);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        static $cache = [];
        if (array_key_exists($type, $cache)) {
            return $cache[$type];
        }

        try {
            /** @var class-string $type */
            $refl = new \ReflectionClass($type);

            do {
                if (!empty($refl->getAttributes(FhirResource::class))) {
                    return $cache[$type] = true;
                }

                $refl = $refl->getParentClass();
            } while ($refl !== false);

            return $cache[$type] = false;
        } catch (\ReflectionException) {
            return $cache[$type] = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, string $resourceType, FHIRSerializationContext $fhirContext, array $context): array
    {
        $data = [];

        if ($fhirContext->enableXmlNamespaces) {
            $data['@xmlns'] = 'http://hl7.org/fhir';
        }

        $properties = self::reflPublicProps($object);
        $metaMap    = $this->getPropertyMetadataMap($object);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if ($propertyName === 'resourceType') {
                continue;
            }

            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            if ($this->shouldOmitValue($value, $fhirContext)) {
                continue;
            }

            $meta   = $metaMap[$propertyName] ?? null;
            $xmlKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey] = $choiceMatch;
                    $xmlKey                       = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context, $fhirContext->includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    } elseif (is_scalar($value)) {
                        $data[$xmlKey] = $this->wrapScalarForXml($value);
                    } else {
                        $normalizedValue = $this->normalizer !== null
                            ? $this->normalizer->normalize($value, 'xml', $context)
                            : $this->normalizeBasicValue($value, 'xml', $context);
                        if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            }

            if ($meta !== null && $meta->propertyKind === 'resource') {
                $wrapped = $this->normalizePolymorphicResourcesXml($value, $meta, $context);
                if ($wrapped !== null) {
                    $data[$xmlKey] = $wrapped;
                }
                continue;
            }

            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayForXML($value, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$xmlKey] = $normalizedArray;
                }
            } elseif ($meta !== null && $meta->xmlSerializedName !== null && is_scalar($value)) {
                $data[$meta->xmlSerializedName] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
            } elseif ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context, $fhirContext->includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } else {
                if (is_scalar($value)) {
                    $normalizedValue = $this->wrapScalarForXml($value);
                } elseif ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, 'xml', $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, 'xml', $context);
                }

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$xmlKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * @param array<mixed>         $array
     * @param array<string, mixed> $context
     *
     * @return array<mixed>|null
     */
    private function normalizeArrayForXML(array $array, FHIRSerializationContext $fhirContext, array $context): ?array
    {
        if (empty($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $item) {
            if ($this->shouldOmitValue($item, $fhirContext)) {
                continue;
            }

            $normalizedItem = $this->normalizer !== null
                ? $this->normalizer->normalize($item, 'xml', $context)
                : $this->normalizeBasicValue($item, 'xml', $context);

            if ($normalizedItem !== null) {
                $result[] = $normalizedItem;
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromXML(array $data, string $type, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        $resolvedType = $type;

        try {
            $reflection            = self::reflClass($resolvedType);
            $object                = $reflection->newInstanceWithoutConstructor();
            $metaMap               = $this->getPropertyMetadataMap($object);
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            foreach ($data as $elementName => $value) {
                if (str_starts_with($elementName, '@') || str_starts_with($elementName, '#')) {
                    continue;
                }

                $property      = self::reflProp($resolvedType, $elementName);
                $choiceMapping = $property === null
                    ? $this->findChoicePropertyByKey($metaMap, $elementName, $resolvedType)
                    : null;

                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    $choiceProp = self::reflProp($resolvedType, $propertyName);
                    if ($choiceProp !== null) {
                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'xml', $context);
                        } else {
                            $rawValue = $this->unwrapXmlValue($value, $phpType);

                            $denormalizedValue = match ($phpType) {
                                'int'   => (int) $rawValue,
                                'float' => (float) $rawValue,
                                'bool'  => filter_var($rawValue, FILTER_VALIDATE_BOOLEAN),
                                default => $rawValue,
                            };
                        }

                        $choiceProp->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                if ($property !== null) {
                    $propertyType     = $this->getPropertyType($property);
                    $propertyMetadata = $metaMap[$elementName] ?? null;
                    $phpItemClass     = $propertyMetadata?->phpItemClass;

                    if ($propertyMetadata !== null && $propertyMetadata->propertyKind === 'resource') {
                        if ($propertyMetadata->isArray) {
                            $items             = is_array($value) && !array_is_list($value) ? [$value] : (array) $value;
                            $denormalizedValue = [];

                            foreach ($items as $item) {
                                $resourceElementName = $this->extractResourceElementName($item);
                                if ($resourceElementName === null) {
                                    continue;
                                }

                                $resolvedClass = $this->typeResolver->resolveResourceType(['resourceType' => $resourceElementName]);

                                if ($resolvedClass !== null && $this->denormalizer !== null && is_array($item)) {
                                    $denormalizedValue[] = $this->denormalizer->denormalize($item[$resourceElementName], $resolvedClass, 'xml', $context);
                                }
                            }

                            $property->setValue($object, $denormalizedValue);
                            continue;
                        }

                        $resourceElementName = $this->extractResourceElementName($value);
                        if ($resourceElementName !== null) {
                            $resolvedClass = $this->typeResolver->resolveResourceType(['resourceType' => $resourceElementName]);

                            if ($resolvedClass !== null && $this->denormalizer !== null) {
                                $denormalizedValue = $this->denormalizer->denormalize($value[$resourceElementName], $resolvedClass, 'xml', $context);
                                $property->setValue($object, $denormalizedValue);
                                continue;
                            }
                        }
                    }

                    if ($propertyMetadata !== null
                        && ($propertyMetadata->propertyKind === 'extension' || $propertyMetadata->propertyKind === 'modifierExtension')
                        && $this->denormalizer !== null
                    ) {
                        $items = $this->unwrapXmlValue($value, 'array');
                        if (is_array($items) && !array_is_list($items)) {
                            $items = [$items];
                        }
                        $denormalizedValue = $this->denormalizeExtensionArray((array) $items, 'xml', $context);
                    } elseif ($phpItemClass !== null && $this->denormalizer !== null) {
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
                        $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);

                        $scalarPhpType = $propertyMetadata?->phpItemClass;
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
                    $this->handleUnknownProperty($elementName, $value, $unknownPropertyPolicy, $object, $elementName);
                }
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
    }
}
