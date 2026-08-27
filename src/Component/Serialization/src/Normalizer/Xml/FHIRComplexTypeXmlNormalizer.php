<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyVariantMetadata;
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
    /**
     * Context key under which deserializeFromXml stashes the source document element, so the
     * denormalizer can recover document order for transparent xml-choice-group properties (the
     * XmlEncoder-decoded array regroups same-named siblings and loses the interleaving). Each
     * denormalize() consumes the element for its own object and re-resolves the matching child
     * element for every complex child it recurses into (arrays paired by document order), so a
     * choice group nested at any depth still receives its own source element.
     */
    public const SOURCE_ELEMENT_CONTEXT_KEY = '__cda_source_element';

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
        // A present-but-empty element (`<code></code>`) is an object with no children, not a missing
        // one. Reading it as such keeps the rest of the document available; ele-1 reports it.
        if (self::isEmptyXmlElement($data)) {
            $data = [];
        }

        if (!is_array($data)) {
            throw new NotNormalizableValueException('Expected array, got ' . gettype($data));
        }

        // Consume the source DOM element (stashed by deserializeFromXml for the root, or threaded in
        // by the parent for a nested object) — it lets a transparent xml-choice-group property
        // recover its document order below. It is unset here so the base context carries no stale
        // element into children; each complex child instead receives its own re-resolved element.
        $sourceElement = null;
        if (isset($context[self::SOURCE_ELEMENT_CONTEXT_KEY]) && $context[self::SOURCE_ELEMENT_CONTEXT_KEY] instanceof \DOMElement) {
            $sourceElement = $context[self::SOURCE_ELEMENT_CONTEXT_KEY];
            unset($context[self::SOURCE_ELEMENT_CONTEXT_KEY]);
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
                : $this->instantiateWithEmptyArrays($reflection);

            $metaMap = $this->getPropertyMetadataMap($object);

            foreach ($data as $elementName => $value) {
                // XmlEncoder attribute keys: @url → url (Extension.url); skip @value and # (XmlEncoder artifacts)
                if (str_starts_with($elementName, '@')) {
                    if ($elementName !== '@value') {
                        $attrName = substr($elementName, 1);
                        $attrProp = self::reflProp($resolvedType, $attrName);
                        if ($attrProp !== null) {
                            $attrProp->setValue(
                                $object,
                                $this->denormalizeXmlAttribute((string) $value, $attrProp, $metaMap[$attrName] ?? null),
                            );
                        }
                    }
                    continue;
                }

                if (str_starts_with($elementName, '#')) {
                    // Inverse of the xmlText-as-text-content rule (see normalizeForXML): when an
                    // element's character data decodes under '#'/'#text' and the target type carries
                    // a scalar `xmlText` property (ST and its subtypes ED, ADXP, ENXP, …), restore
                    // it. Other '#' artifacts (e.g. empty text on attribute-only elements) are
                    // ignored.
                    if (($elementName === '#' || $elementName === '#text') && is_string($value) && $value !== '') {
                        self::reflProp($resolvedType, 'xmlText')?->setValue($object, $value);
                    }
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

                            // A choice variant the generator maps to a PHP scalar (boolean, integer,
                            // decimal) has nowhere to put child elements, so an extension-only
                            // occurrence — `<valueDecimal><extension url="…data-absent-reason"/></valueDecimal>`
                            // — arrives here as the decoded array and raised "Cannot assign array to
                            // property", aborting the document.
                            //
                            // The placeholder records that the element was *present*, which is the
                            // part invariants read: `Parameters.parameter` inv-1 requires
                            // `value.exists()`, and null would fail it on an instance the reference
                            // validator passes. The extension itself is unrepresentable here and is
                            // lost — closing that needs the generator to emit these three variants as
                            // primitive wrapper classes rather than bare bool/int/string.
                            $denormalizedValue = is_array($rawValue) ? match ($phpType) {
                                'int'   => 0,
                                'float' => 0.0,
                                'bool'  => false,
                                default => '',
                            } : match ($phpType) {
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
                    if (is_array($value) && $meta !== null && $meta->fhirType === 'xhtml') {
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
                    } elseif (is_array($value)
                        && $meta !== null
                        && ($meta->propertyKind === 'extension' || $meta->propertyKind === 'modifierExtension')
                    ) {
                        $items             = !array_is_list($value) ? [$value] : $value;
                        $denormalizedValue = $this->denormalizeExtensionArray($items, 'xml', $context);
                    } elseif (is_array($value) && $meta !== null && $meta->phpItemClass !== null) {
                        $phpItemClass = $meta->phpItemClass;
                        $items        = $this->unwrapXmlValue($value, 'array');
                        if (is_array($items) && !array_is_list($items)) {
                            $items = [$items];
                        }
                        // Present yet strips to nothing: one occurrence that was empty, not zero.
                        // See self::isEmptyXmlElement().
                        if ($items === []) {
                            $items = [''];
                        }
                        // Pair each decoded item with its source child element (same-named siblings
                        // decode in document order, so index alignment holds) and thread it down, so
                        // a choiceGroup nested inside this array item can recover its order.
                        $sourceChildren    = $sourceElement !== null ? $this->childElementsByLocalName($sourceElement, $elementName) : [];
                        $denormalizedValue = [];
                        $itemIndex         = 0;
                        foreach ((array) $items as $item) {
                            /** @var class-string $phpItemClass */
                            $itemContext = $context;
                            if (isset($sourceChildren[$itemIndex])) {
                                $itemContext[self::SOURCE_ELEMENT_CONTEXT_KEY] = $sourceChildren[$itemIndex];
                            }
                            $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, 'xml', $itemContext);
                            ++$itemIndex;
                        }
                    } elseif (is_array($value) && $meta !== null && $meta->propertyKind === 'resource') {
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
                            // Captured before the cardinality guard: that call is impure to PHPStan,
                            // which would otherwise widen $this->denormalizer back to nullable.
                            $denormalizer = $this->denormalizer;
                            // Thread this child's source element down so a choiceGroup nested inside
                            // it can recover its document order.
                            $childContext = $context;
                            $sourceChild  = $sourceElement !== null ? ($this->childElementsByLocalName($sourceElement, $elementName)[0] ?? null) : null;
                            if ($sourceChild !== null) {
                                $childContext[self::SOURCE_ELEMENT_CONTEXT_KEY] = $sourceChild;
                            }
                            self::assertSingleValuedElement($elementName, $value, $resolvedType);
                            $denormalizedValue = $denormalizer->denormalize($value, $propertyType, 'xml', $childContext);
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

            // Transparent xml-choice-group: rebuild the ordered list<ChoiceGroupItem> from the
            // source element's children in document order. The XmlEncoder-decoded $data regrouped
            // same-named siblings and lost the interleaving, so this read replaces it. The source
            // element is the root for a top-level object, or the element threaded in by the parent
            // for a nested one — so this works at any depth.
            if ($sourceElement !== null) {
                foreach ($metaMap as $choicePropertyName => $choiceMeta) {
                    if ($choiceMeta->propertyKind !== 'choiceGroup' || $choiceMeta->variants === null) {
                        continue;
                    }
                    self::reflProp($resolvedType, $choicePropertyName)?->setValue(
                        $object,
                        $this->denormalizeChoiceGroup($sourceElement, $choiceMeta->variants, $context),
                    );
                }
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

        // An empty element still claims its complex type: refusing it here left Symfony with no
        // normalizer at all and aborted the whole document. See self::isEmptyXmlElement().
        if (!is_array($data) && !self::isEmptyXmlElement($data)) {
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
    protected function normalizeForXML(object $object, FHIRSerializationContext $fhirContext, array $context): array
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

            // CDA text-carrying types (ST and its subtypes ED, ADXP, ENXP, …) hold the element's
            // character data in a scalar property named `xmlText`. It must serialize as the
            // element's text content, NOT as a <xmlText value="…"/> child. Keyed on the convention
            // name (no standard FHIR primitive uses `xmlText` — they use `value`), and only when it
            // is not itself an xmlAttr.
            if ($propertyName === 'xmlText' && is_scalar($value) && ($meta === null || $meta->xmlSerializedName === null)) {
                $data['#'] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
                continue;
            }

            // Transparent xml-choice-group (FHIR tooling extension xml-choice-group): the property
            // is an ordered list<ChoiceGroupItem> whose heterogeneous children must serialize in
            // true document order, directly under the parent with no wrapper element (e.g. AD:
            // streetAddressLine, city, streetAddressLine). Symfony's XmlEncoder regroups keyed
            // siblings by kind and CDATA-escapes raw strings, so the children are built as a
            // DOMDocumentFragment and injected under the '#' key — XmlEncoder imports a DOMNode
            // verbatim (Encoder/XmlEncoder.php::selectNodeType), preserving order and bypassing
            // escaping, and '#' appends the fragment's children directly to the parent. Root @xmlns
            // and @attributes from M5 are sibling array keys and coexist.
            if ($meta !== null && $meta->propertyKind === 'choiceGroup' && is_array($value)) {
                $data['#'] = $this->buildChoiceGroupFragment($value, $context);
                continue;
            }

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

            // xmlAttr properties emit as XML attributes on the parent element. The value may be a
            // scalar, a backed enum (CDA coded properties - propertyKind 'enum'), or a list of
            // either: V3 SET<cs> attributes such as AD.use carry several codes in one attribute,
            // space-delimited. A value that is none of those yields null here and falls through to
            // the element-emitting branches below, preserving the previous behaviour.
            if ($meta !== null && $meta->xmlSerializedName !== null) {
                $attribute = $this->xmlAttributeValue($value);
                if ($attribute !== null) {
                    $data[$meta->xmlSerializedName] = $attribute;
                    continue;
                }
            }

            // CDA elements in a non-default namespace (sdtc extensions, AU/ADHA extensions): emit
            // under the element's own namespace, stripping the sdtc prefix from the local name
            // where present (e.g. sdtcTelecom -> <telecom xmlns="urn:hl7-org:sdtc">). Inert for
            // standard FHIR, where xmlNamespace is always null.
            if ($meta !== null && $meta->xmlNamespace !== null) {
                $localKey        = $this->cdaLocalElementName($propertyName, $meta);
                $normalizedValue = is_scalar($value)
                    ? $this->wrapScalarForXml($value)
                    : ($this->normalizer !== null
                        ? $this->normalizer->normalize($value, 'xml', $context)
                        : $this->normalizeBasicValue($value, 'xml', $context));
                $normalizedValue = $this->applyElementNamespace($normalizedValue, $meta->xmlNamespace);
                if ($normalizedValue !== null) {
                    $data[$localKey] = $normalizedValue;
                }
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
     * Render an xmlAttr property value as an XML attribute string, or null when it is not
     * representable as one (the caller then falls through to the element-emitting branches).
     *
     * A list renders as its space-delimited members, which is the V3 SET<cs> attribute form used by
     * CDA properties such as AD.use, EN.use, ENXP.qualifier and TEL.use. An empty list yields null
     * rather than an empty attribute; callers skip empty arrays before reaching here, so this is
     * defence in depth.
     */
    private function xmlAttributeValue(mixed $value): ?string
    {
        if (!is_array($value)) {
            return $this->xmlAttributeScalar($value);
        }

        $parts = [];
        foreach ($value as $item) {
            $rendered = $this->xmlAttributeScalar($item);
            if ($rendered === null) {
                // A non-representable member disqualifies the whole attribute rather than silently
                // emitting a partial code list.
                return null;
            }
            $parts[] = $rendered;
        }

        return $parts === [] ? null : implode(' ', $parts);
    }

    /**
     * Render one xmlAttr member. Backed enums (CDA coded properties) contribute their backing code;
     * a bare enum object would otherwise reach the generic normalizer chain, which has no enum
     * normalizer and throws.
     */
    private function xmlAttributeScalar(mixed $value): ?string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * Coerce a decoded XML attribute string to the target property's declared type.
     *
     * Only enum-typed and array-typed properties need conversion; every other property keeps the
     * historical plain-string assignment, so FHIR R4/R4B/R5 attribute handling is untouched - no
     * generated FHIR property is enum- or array-typed on an xmlAttr, CDA is the only consumer.
     */
    private function denormalizeXmlAttribute(string $value, \ReflectionProperty $property, ?PropertyMetadata $meta): mixed
    {
        $type     = $property->getType();
        $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : null;

        if ($typeName === 'array') {
            $codes     = preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY);
            $codes     = $codes === false ? [] : $codes;
            $itemClass = $meta?->phpItemClass !== null ? ltrim($meta->phpItemClass, '\\') : null;

            if ($itemClass !== null && is_a($itemClass, \BackedEnum::class, true)) {
                /** @var class-string<\BackedEnum> $itemClass */
                return array_map(fn (string $code): \BackedEnum => $this->enumCase($itemClass, $code), $codes);
            }

            return $codes;
        }

        if ($typeName !== null && is_a($typeName, \BackedEnum::class, true)) {
            /** @var class-string<\BackedEnum> $typeName */
            return $this->enumCase($typeName, $value);
        }

        return $value;
    }

    /**
     * Resolve one code to its enum case.
     *
     * An unrecognised code throws rather than yielding null: the property cannot represent the
     * value, so a silent null would drop clinical data with no signal. The message names both the
     * code and the enum so a gap between a published CDA document and the generated ValueSet enum
     * is diagnosable at the point of failure.
     *
     * @param class-string<\BackedEnum> $enumClass
     *
     * @throws NotNormalizableValueException When the code is absent from the enum
     */
    private function enumCase(string $enumClass, string $code): \BackedEnum
    {
        $case = $enumClass::tryFrom($code);

        if ($case === null) {
            throw new NotNormalizableValueException(sprintf('Value "%s" is not a valid case of enum "%s".', $code, $enumClass));
        }

        return $case;
    }

    /**
     * Resolve the local XML element name for a namespaced CDA property. An explicit jsonKey wins;
     * otherwise an sdtc-prefixed property name is stripped to its bare local name (sdtcTelecom ->
     * telecom). AU/ADHA extension elements keep their property name.
     */
    private function cdaLocalElementName(string $propertyName, PropertyMetadata $meta): string
    {
        if ($meta->jsonKey !== null) {
            return $meta->jsonKey;
        }

        if (str_starts_with($propertyName, 'sdtc') && strlen($propertyName) > 4) {
            return lcfirst(substr($propertyName, 4));
        }

        return $propertyName;
    }

    /**
     * Declare an element's XML namespace by injecting an @xmlns key. For a list of elements the
     * namespace is applied to each item. Callers wrap scalar values with wrapScalarForXml() first,
     * so the input is always an array (single element or list) on the namespaced path.
     */
    private function applyElementNamespace(mixed $normalized, string $namespace): mixed
    {
        if (is_array($normalized)) {
            if (array_is_list($normalized)) {
                return array_map(fn (mixed $item): mixed => $this->applyElementNamespace($item, $namespace), $normalized);
            }

            $normalized['@xmlns'] = $namespace;
        }

        return $normalized;
    }

    /**
     * Build the ordered children of a transparent xml-choice-group as a DOMDocumentFragment, to be
     * injected under the '#' key so XmlEncoder emits them verbatim and in document order. Each
     * ChoiceGroupItem becomes <{elementName}>{normalized value}</{elementName}>: a string value is
     * the element's text content; an object value is normalized through the serializer and built
     * into the element via buildDomFromArray (attributes + text content + any child elements),
     * interleaved with the other items in list order.
     *
     * Children are created WITHOUT an explicit namespace so that, once XmlEncoder imports the
     * fragment, they inherit the in-scope default namespace (e.g. urn:hl7-org:v3 declared on the
     * CDA root) — matching published CDA. Creating them with createElementNS() instead makes libxml
     * redundantly re-declare xmlns on every child once the element is nested (the nested parent does
     * not re-declare the namespace), breaking byte-level round-trips.
     *
     * @param array<int, mixed>    $items   The choiceGroup property value (a list of ChoiceGroupItem)
     * @param array<string, mixed> $context Serialization context for normalizing object values
     */
    private function buildChoiceGroupFragment(array $items, array $context): \DOMDocumentFragment
    {
        $document = new \DOMDocument();
        $fragment = $document->createDocumentFragment();

        foreach ($items as $item) {
            if (!$item instanceof ChoiceGroupItem) {
                continue;
            }

            $element = $document->createElement($item->elementName);
            $value   = $item->value;

            if (is_string($value)) {
                if ($value !== '') {
                    $element->appendChild($document->createTextNode($value));
                }
            } else {
                $normalized = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'xml', $context)
                    : $this->normalizeBasicValue($value, 'xml', $context);

                if (is_array($normalized)) {
                    $this->buildDomFromArray($document, $element, $normalized);
                } elseif (is_scalar($normalized)) {
                    $element->appendChild($document->createTextNode((string) $normalized));
                }
            }

            $fragment->appendChild($element);
        }

        return $fragment;
    }

    /**
     * Rebuild a transparent xml-choice-group property from the parent's source DOM element: walk its
     * child elements in document order and, for each whose local name matches a variant, append a
     * ChoiceGroupItem with the value denormalized to that variant's phpType. All element names map
     * to the one list property and append (no key-based dispatch) — this is the ordered read the
     * XmlEncoder-decoded array cannot provide.
     *
     * @param list<PropertyVariantMetadata> $variants
     * @param array<string, mixed>          $context
     *
     * @return list<ChoiceGroupItem>
     */
    private function denormalizeChoiceGroup(\DOMElement $element, array $variants, array $context): array
    {
        $variantByName = [];
        foreach ($variants as $variant) {
            $variantByName[$variant->jsonKey] = $variant;
        }

        $items = [];
        foreach ($element->childNodes as $child) {
            if (!$child instanceof \DOMElement) {
                continue;
            }

            $localName = $child->localName;
            $variant   = $localName !== null ? ($variantByName[$localName] ?? null) : null;
            if ($localName === null || $variant === null) {
                continue;
            }

            $items[] = new ChoiceGroupItem($localName, $this->denormalizeChoiceGroupValue($child, $variant, $context));
        }

        return $items;
    }

    /**
     * Denormalize a single choice-group child element to its variant value: a builtin variant
     * yields the element's text content; a class variant decodes the element and denormalizes it to
     * the variant phpType (e.g. ADXP), reusing the standard XML denormalization — including the
     * xmlText and @attribute inverses.
     *
     * @param array<string, mixed> $context
     */
    private function denormalizeChoiceGroupValue(\DOMElement $child, PropertyVariantMetadata $variant, array $context): object|string
    {
        if ($variant->isBuiltin) {
            return $child->textContent;
        }

        $xml     = $child->ownerDocument?->saveXML($child);
        $decoded = is_string($xml) ? $this->xmlEncoder->decode($xml, 'xml') : null;
        if (!is_array($decoded)) {
            return $child->textContent;
        }

        /** @var class-string $phpType */
        $phpType = $variant->phpType;
        if ($this->denormalizer !== null) {
            $value = $this->denormalizer->denormalize($decoded, $phpType, 'xml', $context);
            if (is_object($value)) {
                return $value;
            }
        }

        return $child->textContent;
    }

    /**
     * Direct child elements of $parent with the given local name, in document order. Used to
     * re-resolve the source DOM element for each complex child during denormalization, so the
     * choice-group document-order read keeps working at any nesting depth.
     *
     * @return list<\DOMElement>
     */
    private function childElementsByLocalName(\DOMElement $parent, string $localName): array
    {
        $matches = [];
        foreach ($parent->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $localName) {
                $matches[] = $child;
            }
        }

        return $matches;
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
