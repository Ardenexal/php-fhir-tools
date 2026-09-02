<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKind;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProviderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * JSON normalizer for FHIR complex types (Address, HumanName, etc.).
 *
 * @author Ardenexal
 */
class FHIRComplexTypeJsonNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        string $fhirVersion = 'R4',
        ?FHIRIGTypeRegistry $igTypeRegistry = null,
        private readonly FHIRStructureKindProviderInterface $structureKinds = new FHIRStructureKindProvider(),
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

        if (!$this->metadataExtractor->isComplexType($object)) {
            throw new InvalidArgumentException('Object is not a FHIR complex type');
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

        $resolvedType = $this->typeResolver->resolveComplexType($data, $context) ?? $type;

        if ($resolvedType !== $type && !is_subclass_of($resolvedType, $type)) {
            $resolvedType = $type;
        }

        if ($this->igTypeRegistry !== null) {
            /** @var class-string $resolvedType */
            $sliceClass = $this->igTypeRegistry->resolveSliceClass($resolvedType, $data);
            if ($sliceClass !== null && is_subclass_of($sliceClass, $resolvedType)) {
                $resolvedType = $sliceClass;
            }
        }

        try {
            if (is_a($resolvedType, FHIRComplexExtensionInterface::class, true)
                && isset($data['extension'])
                && is_array($data['extension'])
            ) {
                $subExtensions = $this->denormalizeExtensionArray($data['extension'], 'json', $context);
                $id            = isset($data['id']) && is_string($data['id']) ? $data['id'] : null;

                return $resolvedType::fromSubExtensions($subExtensions, $id);
            }

            $isBackboneElement = $this->structureKinds->declaredKindOf($type) === FHIRStructureKind::BackboneElement;
            $object            = $isBackboneElement
                ? $this->instantiateWithConstructorDefaults($type)
                : $this->instantiateWithDefaults($type);

            $metaMap = $this->getPropertyMetadataMap($object);

            foreach ($data as $elementName => $value) {
                // JSON: skip underscore-prefixed (handled in applyPrimitiveExtensions)
                if (str_starts_with($elementName, '_')) {
                    continue;
                }

                // Skip XML-specific @ keys
                if (str_starts_with($elementName, '@')) {
                    continue;
                }

                $hasProperty   = self::modelAccessor()->hasProperty($resolvedType, $elementName);
                $choiceMapping = !$hasProperty
                    ? $this->findChoicePropertyByKey($metaMap, $elementName, $resolvedType)
                    : null;

                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    if (self::modelAccessor()->hasProperty($resolvedType, $propertyName)) {
                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, 'json', $context);
                        } else {
                            $denormalizedValue = ($fhirType === 'decimal' || $fhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_numeric($value)
                                ? self::decimalToLexicalString($value)
                                : $value;
                        }

                        self::modelAccessor()->writeValue($object, $propertyName, $denormalizedValue);
                        continue;
                    }
                }

                if ($hasProperty) {
                    $meta = $metaMap[$elementName] ?? null;

                    if ($this->denormalizer !== null) {
                        if ($meta !== null && $meta->propertyKind === 'primitive') {
                            $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $resolvedType, $elementName, $value, 'json', $context, $metaMap);
                        } else {
                            $phpItemClass = $meta?->phpItemClass;
                            if (is_array($value)
                                && $meta !== null
                                && ($meta->propertyKind === 'extension' || $meta->propertyKind === 'modifierExtension')
                            ) {
                                $denormalizedValue = $this->denormalizeExtensionArray($value, 'json', $context);
                            } elseif (is_array($value) && $phpItemClass !== null) {
                                $denormalizedValue = [];
                                foreach ($value as $item) {
                                    /** @var class-string $phpItemClass */
                                    $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'json', $context);
                                }
                            } else {
                                $propertyType = $this->getPropertyType($resolvedType, $elementName);
                                if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'json', $context);
                                } else {
                                    $denormalizedValue = $value;
                                }
                            }
                        }
                    } else {
                        $denormalizedValue = $this->denormalizeBasicValue($value, 'json', $context);
                    }

                    self::modelAccessor()->writeValue($object, $elementName, $denormalizedValue);
                }
            }

            $this->applyPrimitiveExtensions($object, $data, $metaMap, 'json', $context);

            if (!$object instanceof FHIRComplexExtensionInterface
                && $this->structureKinds->isExtensionDefinition($type)
            ) {
                $this->copyTypedExtensionValueBack($object);
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
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
            // Walks past any other kind on the way up: a backbone-element ancestor is neither a
            // primitive nor a complex type for this question, and the walk must continue through it.
            $kind = $this->structureKinds->nearestKindAmong(
                $type,
                FHIRStructureKind::PrimitiveType,
                FHIRStructureKind::ComplexType,
            );

            if ($kind !== null) {
                return $cache[$type] = $kind === FHIRStructureKind::ComplexType;
            }

            return $cache[$type] = is_a($type, FHIRComplexExtensionInterface::class, true)
                || $this->structureKinds->isExtensionDefinition($type);
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

        foreach (self::modelAccessor()->publicPropertyNames($object) as $propertyName) {
            // Unwritten and explicitly null both read as null here, and the skip below catches both.
            $value = self::modelAccessor()->readInitializedValue($object, $propertyName);

            if ($value === null || (is_array($value) && empty($value))) {
                continue;
            }

            if (!$includeExtensions && ($propertyName === 'extension' || $propertyName === 'modifierExtension')) {
                continue;
            }

            $meta    = $metaMap[$propertyName] ?? null;
            $jsonKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                        || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
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
            } elseif ($isChoice) {
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue;
                }
                continue;
            }

            if ($meta?->propertyKind === 'primitive' && is_array($value)) {
                $normalizedArray = $this->normalizeArrayWithExtensions($value, $jsonKey, $fhirContext, $context);
                if ($normalizedArray !== null) {
                    $data[$jsonKey] = $normalizedArray['values'];
                    if (isset($normalizedArray['extensions']) && $includeExtensions) {
                        $data['_' . $jsonKey] = $normalizedArray['extensions'];
                    }
                }
                continue;
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

    /**
     * @param array<string, mixed> $context
     */
    private function normalizeChoiceElement(string $propertyName, mixed $value, ?string $format, array $context): mixed
    {
        if ($this->normalizer !== null) {
            return $this->normalizer->normalize($value, $format, $context);
        }

        return $this->normalizeBasicValue($value, $format, $context);
    }
}
