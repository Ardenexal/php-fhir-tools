<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * XML normalizer for FHIR complex types (Address, HumanName, etc.).
 *
 * @author Ardenexal
 */
class FHIRComplexTypeXmlNormalizer extends AbstractFHIRNormalizer
{
    private readonly XmlEncoder $xmlEncoder;

    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        string $fhirVersion = 'R4',
        ?FHIRIGTypeRegistry $igTypeRegistry = null,
    ) {
        parent::__construct($metadataExtractor, $normalizer, $denormalizer, $fhirVersion, $igTypeRegistry);
        $this->xmlEncoder = new XmlEncoder();
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
            $reflection = self::reflClass($resolvedType);

            if (is_a($resolvedType, FHIRComplexExtensionInterface::class, true)
                && isset($data['extension'])
                && is_array($data['extension'])
            ) {
                $raw           = $data['extension'];
                $items         = !array_is_list($raw) ? [$raw] : $raw;
                $subExtensions = $this->denormalizeExtensionArray($items, 'xml', $context);
                $id            = isset($data['@id']) && is_string($data['@id']) ? $data['@id'] : null;

                return $resolvedType::fromSubExtensions($subExtensions, $id);
            }

            $isBackboneElement = !empty($reflection->getAttributes(FHIRBackboneElement::class));
            $object            = $isBackboneElement
                ? $this->instantiateWithConstructorDefaults($reflection)
                : $reflection->newInstanceWithoutConstructor();

            $metaMap = $this->getPropertyMetadataMap($object);

            foreach ($data as $elementName => $value) {
                // XmlEncoder attribute keys: @url → url (Extension.url); skip @value and # (XmlEncoder artifacts)
                if (str_starts_with($elementName, '@')) {
                    if ($elementName !== '@value') {
                        $attrProp = self::reflProp($resolvedType, substr($elementName, 1));
                        if ($attrProp !== null) {
                            $attrProp->setValue($object, (string) $value);
                        }
                    }
                    continue;
                }

                if (str_starts_with($elementName, '#')) {
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
                            $rawValue          = $this->unwrapXmlValue($value, $phpType);
                            $denormalizedValue = match ($phpType) {
                                'int'   => (int) $rawValue,
                                'float' => (float) $rawValue,
                                'bool'  => filter_var($rawValue, FILTER_VALIDATE_BOOLEAN),
                                default => ($fhirType === 'decimal' || $fhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_numeric($rawValue)
                                    ? (string) $rawValue
                                    : $rawValue,
                            };
                        }

                        $choiceProp->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                if ($property === null) {
                    continue;
                }

                $meta = $metaMap[$elementName] ?? null;

                if ($this->denormalizer !== null) {
                    if ($meta !== null && $meta->fhirType === 'xhtml' && is_array($value)) {
                        $xhtmlClass = $this->getFirstNonBuiltinTypeFromProperty($property);
                        if ($xhtmlClass !== null) {
                            /** @var class-string $xhtmlClass */
                            $xhtmlRefl     = self::reflClass($xhtmlClass);
                            $xhtmlInstance = $xhtmlRefl->newInstanceWithoutConstructor();
                            $xhtmlValProp  = self::reflProp($xhtmlClass, 'value');
                            if ($xhtmlValProp !== null) {
                                $xmlString = $this->encodeXhtmlToString($value, 'div');
                                $xhtmlValProp->setValue($xhtmlInstance, $xmlString);
                            }
                            $denormalizedValue = $xhtmlInstance;
                        } else {
                            $denormalizedValue = null;
                        }
                    } elseif ($meta !== null && $meta->propertyKind === 'primitive') {
                        $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $property, $reflection, $value, 'xml', $context, $metaMap);
                    } elseif ($meta !== null
                        && ($meta->propertyKind === 'extension' || $meta->propertyKind === 'modifierExtension')
                        && is_array($value)
                    ) {
                        $items             = !array_is_list($value) ? [$value] : $value;
                        $denormalizedValue = $this->denormalizeExtensionArray($items, 'xml', $context);
                    } elseif ($meta !== null && $meta->phpItemClass !== null && is_array($value)) {
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
                    } elseif ($meta !== null && $meta->propertyKind === 'resource' && is_array($value)) {
                        $resourceElementName = $this->extractResourceElementName($value);
                        if ($resourceElementName !== null) {
                            $resolvedClass = $this->typeResolver->resolveResourceType(['resourceType' => $resourceElementName]);
                            if ($resolvedClass !== null) {
                                $innerData         = is_array($value[$resourceElementName] ?? null) ? $value[$resourceElementName] : $value;
                                $denormalizedValue = $this->denormalizer->denormalize($innerData, $resolvedClass, 'xml', $context);
                                $property->setValue($object, $denormalizedValue);
                                continue;
                            }
                        }
                        $denormalizedValue = null;
                    } else {
                        $propertyType = $this->getPropertyType($property);
                        if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, 'xml', $context);
                        } else {
                            $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);
                            if (is_array($denormalizedValue) && isset($denormalizedValue['@value'])) {
                                $denormalizedValue = $denormalizedValue['@value'];
                            }
                        }
                    }
                } else {
                    $denormalizedValue = $this->denormalizeBasicValue($value, 'xml', $context);
                }

                $property->setValue($object, $denormalizedValue);
            }

            if (!$object instanceof FHIRComplexExtensionInterface
                && !empty($reflection->getAttributes(FHIRExtensionDefinition::class))
            ) {
                $this->copyTypedExtensionValueBack($reflection, $object);
            }

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
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
            $reflection = self::reflClass($type);
            $r          = $reflection;

            do {
                if (!empty($r->getAttributes(FHIRPrimitive::class))) {
                    return $cache[$type] = false;
                }

                if (!empty($r->getAttributes(FHIRComplexType::class))) {
                    return $cache[$type] = true;
                }

                $r = $r->getParentClass();
            } while ($r !== false);

            return $cache[$type] = is_a($type, FHIRComplexExtensionInterface::class, true)
                || !empty($reflection->getAttributes(FHIRExtensionDefinition::class));
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

            if (!$includeExtensions && ($propertyName === 'extension' || $propertyName === 'modifierExtension')) {
                continue;
            }

            $meta   = $metaMap[$propertyName] ?? null;
            $xmlKey = $meta !== null ? ($meta->jsonKey ?? $propertyName) : $propertyName;

            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                        || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
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
            } elseif ($isChoice) {
                $normalizedValue = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'xml', $context)
                    : $this->normalizeBasicValue($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
                continue;
            }

            // Xhtml: XhtmlPrimitive.value is either an array (from XML deserialization) or string (from JSON)
            if ($meta !== null && $meta->fhirType === 'xhtml' && is_object($value)) {
                $xhtmlValProp = self::reflProp($value, 'value');
                if ($xhtmlValProp !== null) {
                    $rawXhtml = $xhtmlValProp->getValue($value);
                    if (is_array($rawXhtml)) {
                        $xhtmlArray           = $rawXhtml;
                        $xhtmlArray['@xmlns'] = 'http://www.w3.org/1999/xhtml';
                        $data[$xmlKey]        = $xhtmlArray;
                        continue;
                    } elseif (is_string($rawXhtml)) {
                        $trimmed    = ltrim($rawXhtml);
                        $xhtmlArray = str_starts_with($trimmed, '<')
                            ? $this->decodeXhtmlToArray($rawXhtml)
                            : ['#' => $rawXhtml];
                        $xhtmlArray['@xmlns'] = 'http://www.w3.org/1999/xhtml';
                        $data[$xmlKey]        = $xhtmlArray;
                        continue;
                    }
                }
            }

            // xmlAttr properties emit as XML attributes on the parent element
            if ($meta !== null && $meta->xmlSerializedName !== null && is_scalar($value)) {
                $data[$meta->xmlSerializedName] = is_bool($value)
                    ? ($value ? 'true' : 'false')
                    : (string) $value;
                continue;
            }

            // Polymorphic resource: wrap with resource type element name
            if ($meta !== null && $meta->propertyKind === 'resource') {
                $wrapped = $this->normalizePolymorphicResourcesXml($value, $meta, $context);
                if ($wrapped !== null) {
                    $data[$xmlKey] = $wrapped;
                }
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

    /**
     * Encode an XmlEncoder-decoded XHTML array back to an XML string using DOMDocument.
     *
     * @param array<string, mixed> $data
     */
    private function encodeXhtmlToString(array $data, string $elementName): string
    {
        $dom  = new \DOMDocument('1.0', 'UTF-8');
        $root = $dom->createElement($elementName);
        $dom->appendChild($root);
        $this->buildDomFromArray($dom, $root, $data);

        return $dom->saveXML($root) ?: '';
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function buildDomFromArray(\DOMDocument $dom, \DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                continue;
            }

            if (str_starts_with($key, '#') && $key !== '#' && $key !== '#text') {
                continue;
            }

            if (str_starts_with($key, '@')) {
                $parent->setAttribute(substr($key, 1), (string) $value);
            } elseif ($key === '#' || $key === '#text') {
                if (is_array($value)) {
                    foreach ($value as $textItem) {
                        if ($textItem !== '' && $textItem !== null) {
                            $parent->appendChild($dom->createTextNode((string) $textItem));
                        }
                    }
                } elseif ($value !== '' && $value !== null) {
                    $parent->appendChild($dom->createTextNode((string) $value));
                }
            } elseif (is_array($value) && array_is_list($value)) {
                foreach ($value as $item) {
                    $child = $dom->createElement($key);
                    $parent->appendChild($child);
                    if (is_array($item)) {
                        $this->buildDomFromArray($dom, $child, $item);
                    } elseif ($item !== '' && $item !== null) {
                        $child->appendChild($dom->createTextNode((string) $item));
                    }
                }
            } elseif (is_array($value)) {
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                $this->buildDomFromArray($dom, $child, $value);
            } else {
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                if ($value !== '' && $value !== null) {
                    $child->appendChild($dom->createTextNode((string) $value));
                }
            }
        }
    }

    /**
     * Decode an XHTML XML string back to the XmlEncoder array format.
     *
     * @return array<string, mixed>
     */
    private function decodeXhtmlToArray(string $xmlString): array
    {
        $decoded = $this->xmlEncoder->decode($xmlString, 'xml');

        if (!is_array($decoded)) {
            return [];
        }

        return $this->transformXhtmlArrayForReencoding($decoded);
    }

    /**
     * Recursively transform an XmlEncoder-decoded XHTML array so it can be re-encoded correctly.
     *
     * @param array<string, mixed> $data
     *
     * @return array<array-key, mixed>
     */
    private function transformXhtmlArrayForReencoding(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($key === '#text') {
                $combined    = is_array($value) ? implode('', $value) : (string) $value;
                $result['#'] = ($result['#'] ?? '') . $combined;
                continue;
            }

            if (is_array($value) && array_is_list($value)) {
                $transformed = [];
                foreach ($value as $item) {
                    if (is_scalar($item)) {
                        $transformed[] = ['#' => (string) $item];
                    } elseif (is_array($item)) {
                        $transformed[] = $this->transformXhtmlArrayForReencoding($item);
                    } else {
                        $transformed[] = $item;
                    }
                }
                $result[$key] = $transformed;
            } elseif (is_array($value)) {
                $result[$key] = $this->transformXhtmlArrayForReencoding($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
