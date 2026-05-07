<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * XML normalizer for FHIR backbone elements.
 *
 * @author Ardenexal
 */
class FHIRBackboneElementXmlNormalizer extends AbstractFHIRNormalizer
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

        if (!$this->metadataExtractor->isBackboneElement($object)) {
            throw new InvalidArgumentException('Object is not a FHIR backbone element');
        }

        return $this->normalizeForXML($object, FHIRSerializationContext::fromSymfonyContext($context), $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

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
            $reflection = self::reflClass($type);
            $object     = $this->instantiateWithConstructorDefaults($reflection);
            $metaMap    = $this->getPropertyMetadataMap($object);

            foreach ($data as $elementName => $value) {
                if (str_starts_with($elementName, '@') || str_starts_with($elementName, '#')) {
                    continue;
                }

                $property      = self::reflProp($type, $elementName);
                $choiceMapping = $property === null
                    ? $this->findChoicePropertyByKey($metaMap, $elementName, $type)
                    : null;

                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    $choiceProp = self::reflProp($type, $propertyName);
                    if ($choiceProp !== null) {
                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'xml', $context);
                        } else {
                            $rawValue          = $this->unwrapXmlValue($value, $phpType);
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

                if ($property === null) {
                    continue;
                }
                $meta     = $metaMap[$elementName] ?? null;

                if ($elementName === 'extension' || $elementName === 'modifierExtension') {
                    $items             = is_array($value) && !array_is_list($value) ? [$value] : (array) $value;
                    $denormalizedValue = array_values($this->denormalizeExtensionArray(array_values($items), 'xml', $context));
                } elseif ($meta !== null && $meta->propertyKind === 'resource') {
                    $resourceElementName = $this->extractResourceElementName($value);
                    if ($resourceElementName !== null && $this->denormalizer !== null && is_array($value) && isset($value[$resourceElementName])) {
                        $resolvedClass = $this->typeResolver->resolveResourceType(['resourceType' => $resourceElementName]);
                        if ($resolvedClass !== null) {
                            $denormalizedValue = $this->denormalizer->denormalize($value[$resourceElementName], $resolvedClass, 'xml', $context);
                            $property->setValue($object, $denormalizedValue);
                            continue;
                        }
                    }
                    $denormalizedValue = null;
                } elseif ($meta !== null && $meta->phpItemClass !== null && $this->denormalizer !== null) {
                    $phpItemClass = $meta->phpItemClass;
                    $items        = $this->unwrapXmlValue($value, 'array');
                    if (is_array($items) && !array_is_list($items)) {
                        $items = [$items];
                    }
                    $denormalizedValue = [];
                    foreach ((array) $items as $item) {
                        /** @var class-string $phpItemClass */
                        $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'xml', $context);
                    }
                } elseif ($this->denormalizer !== null) {
                    $propertyType = $this->getPropertyType($property);
                    if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                        $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'xml', $context);
                    } else {
                        $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);
                        if (is_array($denormalizedValue) && isset($denormalizedValue['@value'])) {
                            $denormalizedValue = $denormalizedValue['@value'];
                        }

                        $scalarPhpType = $meta?->phpItemClass;
                        if ($scalarPhpType === 'int') {
                            $denormalizedValue = (int) $denormalizedValue;
                        } elseif ($scalarPhpType === 'float') {
                            $denormalizedValue = (float) $denormalizedValue;
                        } elseif ($scalarPhpType === 'bool') {
                            $denormalizedValue = filter_var($denormalizedValue, FILTER_VALIDATE_BOOLEAN);
                        }
                    }
                } else {
                    $propertyType      = $this->getPropertyType($property);
                    $denormalizedValue = ($propertyType !== null && !$this->isBuiltinType($propertyType))
                        ? null
                        : $this->denormalizeBasicValue($value, 'xml', $context);
                }

                $property->setValue($object, $denormalizedValue);
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
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
            return $cache[$type] = !empty(self::reflClass($type)->getAttributes(FHIRBackboneElement::class));
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
    private function normalizeForXML(object $object, FHIRSerializationContext $fhirContext, array $context): array
    {
        $data              = [];
        $metaMap           = $this->getPropertyMetadataMap($object);
        $includeExtensions = $fhirContext->includeExtensions;

        $properties = self::reflPublicProps($object);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);

            if ($value === null || (is_array($value) && empty($value))) {
                continue;
            }

            $meta   = $metaMap[$propertyName] ?? null;
            $xmlKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                if ($includeExtensions) {
                    $normalizedValue = $this->normalizeExtensions($value, 'xml', $context);
                    if ($normalizedValue !== null && !empty($normalizedValue)) {
                        $data[$propertyName] = $normalizedValue;
                    }
                }
                continue;
            }

            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey] = $choiceMatch;
                    $xmlKey                       = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context, $includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$xmlKey] = $normalizedValue;
                        }
                    } elseif (is_scalar($value)) {
                        $data[$xmlKey] = $this->wrapScalarForXml($value);
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

            // Polymorphic resource: wrap with resource type element name
            if ($meta !== null && $meta->propertyKind === 'resource') {
                $wrapped = $this->normalizePolymorphicResourcesXml($value, $meta, $context);
                if ($wrapped !== null) {
                    $data[$xmlKey] = $wrapped;
                }
                continue;
            }

            // xmlAttr properties emit as XML attributes on the parent element
            if ($meta !== null && $meta->xmlSerializedName !== null && is_scalar($value)) {
                $data[$meta->xmlSerializedName] = is_bool($value)
                    ? ($value ? 'true' : 'false')
                    : (string) $value;
                continue;
            }

            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context, $includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } elseif (is_scalar($value)) {
                $data[$xmlKey] = $this->wrapScalarForXml($value);
            } else {
                $normalizedValue = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'xml', $context)
                    : $this->normalizeBasicValue($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }
}
