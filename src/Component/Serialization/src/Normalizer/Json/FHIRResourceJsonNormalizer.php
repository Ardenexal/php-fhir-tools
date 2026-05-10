<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

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
 * JSON normalizer for FHIR resource classes.
 *
 * @author Ardenexal
 */
class FHIRResourceJsonNormalizer extends AbstractFHIRNormalizer
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
            $debugInfo = FHIRSerializationDebugInfo::forNormalization('json', null, get_class($object), self::class, $context);
        }

        try {
            $result = $this->normalizeForJSON($object, $resourceType, $fhirContext, $context);

            if ($debugInfo !== null) {
                $context['fhir_debug_info'] = $debugInfo->completed();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($e instanceof FHIRSerializationException || $e instanceof InvalidArgumentException) {
                throw $e;
            }

            throw FHIRSerializationException::formatError('json', $e->getMessage(), ['object_type' => get_class($object), 'resource_type' => $resourceType]);
        }
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
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
            $debugInfo = FHIRSerializationDebugInfo::forDenormalization('json', null, $type, self::class, $context);
        }

        try {
            $result = $this->denormalizeFromJSON($data, $type, $fhirContext, $context);

            if ($debugInfo !== null) {
                $context['fhir_debug_info'] = $debugInfo->completed();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($e instanceof FHIRSerializationException || $e instanceof NotNormalizableValueException) {
                throw $e;
            }

            throw FHIRSerializationException::formatError('json', $e->getMessage(), ['target_type' => $type, 'data_keys' => array_keys($data)]);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        if (!isset($data['resourceType'])) {
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
    private function normalizeForJSON(object $object, string $resourceType, FHIRSerializationContext $fhirContext, array $context): array
    {
        $data                 = [];
        $data['resourceType'] = $resourceType;

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

            $meta    = $metaMap[$propertyName] ?? null;
            $jsonKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey, $resolvedFhirType] = $choiceMatch;
                    $jsonKey                                         = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context, $fhirContext->includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if (isset($normalizedValue['extensions']) && $fhirContext->includeExtensions) {
                                $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                            }
                        }
                    } else {
                        $normalizedValue = $this->normalizer !== null
                            ? $this->normalizer->normalize($value, 'json', $context)
                            : $this->normalizeBasicValue($value, 'json', $context);
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

            if (is_array($value)) {
                $normalizedArray = $this->normalizeArrayWithExtensions($value, $jsonKey, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$jsonKey] = $normalizedArray['values'];
                    if (isset($normalizedArray['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $jsonKey] = $normalizedArray['extensions'];
                    }
                }
            } elseif ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context, $fhirContext->includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions']) && $fhirContext->includeExtensions) {
                        $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                    }
                }
            } else {
                $normalizedValue = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'json', $context)
                    : $this->normalizeBasicValue($value, 'json', $context);

                $normalizedValue = $this->castNumericScalarForJson($normalizedValue, $meta);

                if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext)) {
                    $data[$jsonKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
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

            if (is_object($item) && $this->metadataExtractor->isPrimitiveType($item)) {
                $primitiveResult = $this->normalizePrimitiveWithExtensions($item, 'json', $context, $fhirContext->includeExtensions);
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
                if (is_array($item)) {
                    $normalizedItem = $item;
                } elseif ($this->normalizer !== null) {
                    $normalizedItem = $this->normalizer->normalize($item, 'json', $context);
                } else {
                    $normalizedItem = $this->normalizeBasicValue($item, 'json', $context);
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

        if ($hasExtensions) {
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
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromJSON(array $data, string $type, FHIRSerializationContext $fhirContext, array $context): mixed
    {
        if (!isset($data['resourceType'])) {
            if ($fhirContext->isStrictValidation()) {
                throw FHIRSerializationException::validationError('Missing required resourceType field');
            }
            throw new NotNormalizableValueException('Missing required resourceType field');
        }

        $resourceType = $data['resourceType'];
        if (!is_string($resourceType) || empty($resourceType)) {
            throw new NotNormalizableValueException('resourceType must be a non-empty string');
        }

        $resolvedType = $this->typeResolver->resolveResourceType($data) ?? $type;

        if ($resolvedType !== $type && !is_subclass_of($resolvedType, $type)) {
            throw new NotNormalizableValueException(sprintf('Resolved type "%s" is not compatible with expected type "%s"', $resolvedType, $type));
        }

        try {
            $reflection            = self::reflClass($resolvedType);
            $object                = $reflection->newInstanceWithoutConstructor();
            $metaMap               = $this->getPropertyMetadataMap($object);
            $unknownPropertyPolicy = $fhirContext->unknownElementPolicy;

            foreach ($data as $elementName => $value) {
                if (str_starts_with($elementName, '_') || str_starts_with($elementName, '@')) {
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
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'json', $context);
                        } else {
                            $denormalizedValue = ($fhirType === 'decimal' || $fhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_numeric($value)
                                ? (string) $value
                                : $value;
                        }

                        $choiceProp->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                if ($property !== null) {
                    $meta         = $metaMap[$elementName] ?? null;
                    $phpItemClass = $meta?->phpItemClass;

                    if ($meta !== null
                        && ($meta->propertyKind === 'extension' || $meta->propertyKind === 'modifierExtension')
                        && is_array($value)
                    ) {
                        $denormalizedValue = $this->denormalizeExtensionArray($value, 'json', $context);
                    } elseif ($phpItemClass !== null && $this->denormalizer !== null && is_array($value)) {
                        $denormalizedValue = [];
                        foreach ($value as $item) {
                            $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'json', $context);
                        }
                    } elseif ($this->denormalizer !== null && $meta !== null && $meta->propertyKind === 'primitive') {
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
                    $this->handleUnknownProperty($elementName, $value, $unknownPropertyPolicy, $object, $elementName);
                }
            }

            $this->applyPrimitiveExtensions($reflection, $object, $data, $metaMap, 'json', $context);

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
        }
    }
}
