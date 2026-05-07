<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * JSON normalizer for FHIR backbone elements.
 *
 * @author Ardenexal
 */
class FHIRBackboneElementJsonNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
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

        return $this->normalizeForJSON($object, FHIRSerializationContext::fromSymfonyContext($context), $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
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
                if (str_starts_with($elementName, '_')) {
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

                    if ($elementName === 'extension' || $elementName === 'modifierExtension') {
                        $denormalizedValue = is_array($value)
                            ? array_values($this->denormalizeExtensionArray(array_values($value), 'json', $context))
                            : null;
                    } else {
                        $meta         = $metaMap[$elementName] ?? null;
                        $phpItemClass = $meta?->phpItemClass;

                        if ($this->denormalizer !== null) {
                            if ($phpItemClass !== null && is_array($value)) {
                                $denormalizedValue = [];
                                foreach ($value as $item) {
                                    /** @var class-string $phpItemClass */
                                    $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'json', $context);
                                }
                            } elseif ($meta !== null && $meta->propertyKind === 'primitive') {
                                $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $property, $reflection, $value, 'json', $context, $metaMap);
                            } else {
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'json', $context);
                                } else {
                                    $denormalizedValue = $value;
                                }
                            }
                        } else {
                            $propertyType = $this->getPropertyType($property);
                            $denormalizedValue = ($propertyType !== null && !$this->isBuiltinType($propertyType))
                                ? null
                                : $this->denormalizeBasicValue($value, 'json', $context);
                        }
                    }

                    $property->setValue($object, $denormalizedValue);
                }
            }

            $this->applyPrimitiveExtensions($reflection, $object, $data, $metaMap, 'json', $context);

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
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
    private function normalizeForJSON(object $object, FHIRSerializationContext $fhirContext, array $context): array
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

            $meta    = $metaMap[$propertyName] ?? null;
            $jsonKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            if ($propertyName === 'extension' || $propertyName === 'modifierExtension') {
                if ($includeExtensions) {
                    $normalizedValue = $this->normalizeExtensions($value, 'json', $context);
                    if ($normalizedValue !== null && !empty($normalizedValue)) {
                        $data[$propertyName] = $normalizedValue;
                    }
                }
                continue;
            }

            if ($meta !== null && $meta->isChoice && !empty($meta->variants)) {
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey, $resolvedFhirType] = $choiceMatch;
                    $jsonKey                                         = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context, $includeExtensions);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if ($includeExtensions && isset($normalizedValue['extensions'])) {
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
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue;
                        }
                    }
                    continue;
                }
            }

            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context, $includeExtensions);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if ($includeExtensions && isset($normalizedValue['extensions'])) {
                        $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                    }
                }
            } else {
                $normalizedValue = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'json', $context)
                    : $this->normalizeBasicValue($value, 'json', $context);

                $normalizedValue = $this->castNumericScalarForJson($normalizedValue, $meta);

                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }
}
