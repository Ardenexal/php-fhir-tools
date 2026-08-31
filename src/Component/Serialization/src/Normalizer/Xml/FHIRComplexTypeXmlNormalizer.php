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
                : $this->instantiateWithDefaults($reflection);

            $metaMap = $this->getPropertyMetadataMap($object);
            $data    = $this->remapNamespacedElements($data, $metaMap, $object, $sourceElement);

            // CDA narrative is read from the source DOM, not from $data: XmlEncoder's decode
            // regroups same-named siblings and loses their order, which would silently reshuffle a
            // narrative block. The element names handled here are skipped in the loop below.
            $narrativeElements = $this->restoreNarrativeProperties($object, $metaMap, $sourceElement);

            foreach ($data as $elementName => $value) {
                if (isset($narrativeElements[$elementName])) {
                    continue;
                }

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
                    } elseif (is_string($value) && $meta !== null && self::expectsComplexValue($meta)) {
                        // An element whose content is only character data (`<name>Example Clinic</name>`)
                        // decodes to a bare PHP string, so there is no array for the object path to
                        // consume. Left alone it falls through to the builtin branch below and the raw
                        // string is assigned to a complex-typed property — a `list<ON>` holding a
                        // string, which the declaration cannot catch because the property is typed
                        // `array`. Build the object from the source DOM element instead, which is
                        // where the character data still lives in its original form.
                        //
                        // The empty string is included deliberately: `<name/>` and `<name></name>`
                        // are present-but-empty elements, and excluding them left exactly the shape
                        // above — `$organization->name[0]` was a string, so reading `->item` on it
                        // warned and yielded null. They build an ON with no members, because an
                        // empty element has no child nodes for the choice-group read to walk. Note
                        // this is NOT the same as whitespace-only content: `<name> </name>` carries
                        // a text node, which is content when nothing else is in the element, and
                        // still comes back as a one-member group.
                        $denormalizedValue = $this->denormalizeCharacterDataElement(
                            $value,
                            $meta,
                            $property,
                            $elementName,
                            $sourceElement,
                            $context,
                        );
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
        /** @var array<string, list<mixed>> $namespacedData Buffered non-default-namespace elements, merged below */
        $namespacedData    = [];
        $metaMap           = $this->getPropertyMetadataMap($object);
        $includeExtensions = $fhirContext->includeExtensions;

        $properties = self::orderByContentModel(self::reflPublicProps($object), $this->contentModelOrder($object));

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

            // CDA narrative: the markup is held as a plain string, not an XhtmlPrimitive object, and
            // must emit as element content in the document's own namespace (urn:hl7-org:v3) rather
            // than escaped into a value attribute. `Section.text` is the only xhtml-typed element in
            // the CDA package — it carries `representation: cdaText` upstream, and nothing else does
            // — so the xhtml type alone identifies it and no extra metadata is needed.
            if ($meta !== null && $meta->fhirType === 'xhtml' && is_string($value)) {
                $data[$xmlKey] = ['#' => $this->buildNarrativeFragment($value)];

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
                    // Buffered rather than written straight in: the stripped local name can equal a
                    // sibling property's element name (Patient.raceCode vs Patient.sdtcRaceCode), and
                    // a keyed write would let whichever property is declared later silently drop the
                    // other. Merged after the loop so the outcome does not depend on declaration
                    // order.
                    //
                    // An array-typed property already normalized to a list of elements; its members
                    // are buffered individually so they stay siblings rather than becoming one
                    // nested list.
                    if (is_array($normalizedValue) && array_is_list($normalizedValue)) {
                        foreach ($normalizedValue as $item) {
                            $namespacedData[$localKey][] = $item;
                        }
                    } else {
                        $namespacedData[$localKey][] = $normalizedValue;
                    }
                    // Reserve this element's position with a null placeholder the fold recognises.
                    // Overwriting an existing PHP array key keeps its original position, so the fold
                    // writes the value back here rather than appending it after every sibling — which
                    // is what put an AU extension element last regardless of its content-model
                    // position. Buffering still owns the collision case.
                    $data[$localKey] ??= null;
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

        // Fold buffered namespaced elements in. Where the local name is free the value keeps its
        // natural shape; where it collides with an element the loop already emitted, the two become
        // a list — XmlEncoder renders that as repeated siblings and honours the per-item @xmlns, so
        // <raceCode/> and <raceCode xmlns="urn:hl7-org:sdtc"/> both survive as distinct elements.
        foreach ($namespacedData as $localKey => $values) {
            // A reserved slot holds null and is not a sibling value; anything else is a real element
            // the loop emitted under the same local name, which becomes the first of the merged list.
            $reserved = array_key_exists($localKey, $data) && $data[$localKey] === null;
            $existing = (array_key_exists($localKey, $data) && !$reserved) ? [$data[$localKey]] : [];
            $merged   = array_merge($existing, $values);

            $data[$localKey] = count($merged) === 1 ? $merged[0] : $merged;
        }

        return $data;
    }

    /**
     * The type's published content-model order, or an empty list when it declares none.
     *
     * Empty is the answer for standard FHIR: its generated resources and complex types already
     * declare their elements in published order, so reflection order is already right and must not be
     * disturbed. CDA logical models override this — see FHIRLogicalModelXmlNormalizer.
     *
     * @return list<string> property names in published order
     */
    protected function contentModelOrder(object $object): array
    {
        return [];
    }

    /**
     * Reorder properties to match a published content model, leaving them untouched when there is no
     * order to apply.
     *
     * Ordering the property list is what makes one change enough: every emit branch in
     * {@see normalizeForXML()} — element, XML attribute, text content, and the wrapper-less
     * choice-group fragment under the `#` key — writes into `$data` in iteration order, so all of them
     * land correctly without knowing that ordering exists.
     *
     * The `#` case is currently untested rather than verified, because nothing exercises it: the only
     * choice-group-bearing CDA types are `AD` and `EN`, both of which extend `ANY` and so have no
     * inherited elements to reorder, and their eight subclasses (`AuAddress`, `PN`, `ON`, …) add no
     * properties of their own. For all ten, this sort is a no-op. A future CDA type that both carries a
     * choice group and inherits elements would be the first real exercise of that interaction.
     *
     * Reflection order cannot serve as the content model. `ReflectionClass::getProperties()` returns a
     * class's own properties first and its ancestors' last, so CDA's `InfrastructureRoot` elements —
     * `realmCode`, `typeId`, `templateId`, which the content model puts first — come last on every act
     * that inherits them. Nor can the class hierarchy: AU's `completionCode` is declared on the child
     * yet belongs mid-sequence in the parent's elements.
     *
     * A property the order does not name keeps its reflection position, after every named one. PHP's
     * sort has been stable since 8.0, so equal ranks preserve that relative order.
     *
     * @param list<\ReflectionProperty> $properties
     * @param list<string>              $order      property names in published order; empty to leave as-is
     *
     * @return list<\ReflectionProperty> the same properties in published order
     */
    private static function orderByContentModel(array $properties, array $order): array
    {
        if ($order === []) {
            return $properties;
        }

        // Case-sensitive by construction: CDA `Section` declares both an `ID` attribute and an `id`
        // element, and folding case would rank them as one property.
        $rank = array_flip($order);

        usort(
            $properties,
            static fn (\ReflectionProperty $left, \ReflectionProperty $right): int => ($rank[$left->getName()] ?? PHP_INT_MAX) <=> ($rank[$right->getName()] ?? PHP_INT_MAX),
        );

        return $properties;
    }

    /**
     * Re-key decoded elements that were emitted under a different local name in their own XML
     * namespace, so the existing property loop below can resolve them by property name as usual.
     *
     * normalizeForXML emits an sdtc property under its bare local name (sdtcStatusCode ->
     * <statusCode xmlns="urn:hl7-org:sdtc">), so the decoded key is `statusCode` and never matches
     * the `sdtcStatusCode` property. This is the inverse. Only properties whose emitted local name
     * actually differs from the property name need remapping, which excludes the AU/ADHA extension
     * elements — they emit under their own name and already resolve.
     *
     * Symfony's XmlEncoder decode is namespace-blind: a v3 <raceCode> and an sdtc <raceCode> collapse
     * into one grouped key with no namespace anywhere in the result. The source DOM element is the
     * only place the distinction survives, so it drives the split whenever it is available. Decoded
     * siblings and DOM children are paired by index, which is sound precisely because both sequences
     * are namespace-blind in the same way and both keep document order; a length mismatch means the
     * pairing cannot be trusted, so the entry is left alone rather than guessed at.
     *
     * @param array<array-key, mixed>         $data
     * @param array<string, PropertyMetadata> $metaMap
     * @param object                          $subject The instance being populated, used for property lookup
     *
     * @return array<array-key, mixed>
     */
    private function remapNamespacedElements(array $data, array $metaMap, object $subject, ?\DOMElement $sourceElement): array
    {
        /** @var array<string, list<array{property: string, namespace: string}>> $candidates */
        $candidates = [];
        foreach ($metaMap as $propertyName => $meta) {
            if ($meta->xmlNamespace === null) {
                continue;
            }

            $localName = $this->cdaLocalElementName($propertyName, $meta);
            if ($localName === $propertyName) {
                // Emitted under its own name (AU/ADHA extensions, unprefixed sdtc properties) —
                // the default property-name lookup already resolves it.
                continue;
            }

            $candidates[$localName][] = ['property' => $propertyName, 'namespace' => $meta->xmlNamespace];
        }

        foreach ($candidates as $localName => $matches) {
            if (!array_key_exists($localName, $data)) {
                continue;
            }

            $collides = self::reflProp($subject, $localName) !== null;

            if ($sourceElement === null) {
                // No DOM to disambiguate with. A single unambiguous candidate can still be re-keyed
                // blindly; a collision cannot, so it keeps the previous behaviour.
                if (!$collides && count($matches) === 1) {
                    $data[$matches[0]['property']] = $data[$localName];
                    unset($data[$localName]);
                }

                continue;
            }

            $decoded     = $data[$localName];
            $items       = is_array($decoded) && array_is_list($decoded) ? $decoded : [$decoded];
            $domChildren = $this->childElementsByLocalName($sourceElement, $localName);

            if (count($domChildren) !== count($items)) {
                continue;
            }

            /** @var array<string, list<mixed>> $buckets */
            $buckets = [];
            foreach ($items as $index => $item) {
                $namespace = $domChildren[$index]->namespaceURI;
                $target    = $localName;

                foreach ($matches as $match) {
                    if ($match['namespace'] === $namespace) {
                        $target = $match['property'];
                        break;
                    }
                }

                $buckets[$target][] = $item;
            }

            unset($data[$localName]);
            foreach ($buckets as $property => $values) {
                // A lone element decodes to a bare map rather than a list; hand the loop the same
                // shape it would have seen had the element not needed remapping.
                $data[$property] = count($values) === 1 ? $values[0] : $values;
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
     * Set every string-typed xhtml property on a freshly denormalized object from its element in the
     * source DOM, and return the element names consumed so the caller can skip them.
     *
     * Reading from the DOM rather than the decoded array is not an optimisation: XmlEncoder's decode
     * regroups same-named siblings, so `<p/><table/><p/>` arrives as a two-element `p` plus a
     * `table` with the interleaving lost. A narrative reshuffled that way would still be
     * well-formed, which is exactly why it has to be read in document order instead.
     *
     * @param array<string, PropertyMetadata> $metaMap
     *
     * @return array<string, true> element names whose value has been consumed here
     */
    private function restoreNarrativeProperties(object $object, array $metaMap, ?\DOMElement $sourceElement): array
    {
        if ($sourceElement === null) {
            return [];
        }

        $consumed = [];
        foreach ($metaMap as $propertyName => $meta) {
            if ($meta->fhirType !== 'xhtml') {
                continue;
            }

            $property = self::reflProp($object, $propertyName);
            if ($property === null) {
                continue;
            }
            $type = $property->getType();
            if (!$type instanceof \ReflectionNamedType || $type->getName() !== 'string') {
                continue;
            }

            $elementName = $this->cdaLocalElementName($propertyName, $meta);
            $children    = $this->childElementsByLocalName($sourceElement, $elementName);
            if ($children === []) {
                continue;
            }

            $property->setValue($object, $this->narrativeMarkupFrom($children[0]));
            $consumed[$elementName] = true;
        }

        return $consumed;
    }

    /**
     * Serialize an element's children back to a markup string, dropping the namespace declarations
     * the parser resolved onto them.
     *
     * The inherited default namespace is deliberately not written back: it was never in the string
     * the caller supplied, it is re-established on emit by the document root, and repeating it on
     * every node would grow the markup on each round trip. Elements are rebuilt rather than
     * string-edited so nothing depends on matching a namespace declaration by pattern.
     */
    private function narrativeMarkupFrom(\DOMElement $element): string
    {
        $target = new \DOMDocument();
        $markup = '';

        foreach ($element->childNodes as $child) {
            $copy = $this->copyWithoutNamespaces($child, $target);
            if ($copy === null) {
                continue;
            }
            $markup .= (string) $target->saveXML($copy);
        }

        return $markup;
    }

    /**
     * Recursively copy a narrative node into another document with no namespace bound to any
     * element or attribute. Comments and processing instructions are dropped; text is preserved as
     * text, CDATA sections included — DOMCdataSection extends DOMText, so they take the same branch.
     */
    private function copyWithoutNamespaces(\DOMNode $node, \DOMDocument $target): ?\DOMNode
    {
        if ($node instanceof \DOMText) {
            return $target->createTextNode($node->nodeValue ?? '');
        }

        // A null localName means the node carries no element name to copy (DOM allows it on nodes
        // that are not named elements), so there is nothing to rebuild.
        if (!$node instanceof \DOMElement || $node->localName === null) {
            return null;
        }

        $copy = $target->createElement($node->localName);
        foreach ($node->attributes as $attribute) {
            // Namespaced attributes are dropped along with the namespaces themselves; an attribute
            // with no local name cannot be re-created.
            if ($attribute->namespaceURI !== null || $attribute->localName === null) {
                continue;
            }

            $copy->setAttribute($attribute->localName, $attribute->value);
        }

        foreach ($node->childNodes as $child) {
            $childCopy = $this->copyWithoutNamespaces($child, $target);
            if ($childCopy !== null) {
                $copy->appendChild($childCopy);
            }
        }

        return $copy;
    }

    /**
     * Build a CDA narrative block as a DOMDocumentFragment, to be injected under the '#' key so
     * XmlEncoder emits it verbatim as element content.
     *
     * The narrative is a StrucDoc markup tree — paragraphs, lists, tables — that this library keeps
     * as a plain string on the model rather than as a generated class hierarchy (`hl7.cda.uv.core`
     * publishes no StrucDoc StructureDefinition, so there is nothing to generate from, and callers
     * overwhelmingly already hold the markup as text). Storing a string does not mean emitting one:
     * CDA requires real child elements here, so the string is parsed on the way out.
     *
     * A fragment rather than a decoded array because XmlEncoder's decode regroups same-named
     * siblings and destroys interleaved document order — fatal for narrative, where
     * `<p>/<table>/<p>` must stay in that order. Children are created WITHOUT an explicit namespace
     * so they inherit the in-scope default (urn:hl7-org:v3 declared on the CDA root), matching
     * published CDA; createElementNS() would make libxml re-declare xmlns on every child.
     *
     * Markup that will not parse, and plain text with no markup at all, both become text content:
     * a narrative is author-supplied and must never be able to break document serialization.
     */
    private function buildNarrativeFragment(string $narrative): \DOMDocumentFragment
    {
        $document = new \DOMDocument();
        $fragment = $document->createDocumentFragment();

        if (ltrim($narrative) === '' || !str_contains($narrative, '<')) {
            $fragment->appendChild($document->createTextNode($narrative));

            return $fragment;
        }

        // Parse under a throwaway root so multiple top-level nodes are allowed, with entity loading
        // disabled and errors captured rather than raised.
        $source   = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $parsed   = $source->loadXML(
            '<narrative>' . $narrative . '</narrative>',
            \LIBXML_NONET | \LIBXML_NOENT,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$parsed || $source->documentElement === null) {
            $fragment->appendChild($document->createTextNode($narrative));

            return $fragment;
        }

        foreach ($source->documentElement->childNodes as $child) {
            $fragment->appendChild($document->importNode($child, true));
        }

        return $fragment;
    }

    /**
     * Build the ordered children of a transparent xml-choice-group as a DOMDocumentFragment, to be
     * injected under the '#' key so XmlEncoder emits them verbatim and in document order. Each
     * ChoiceGroupItem becomes <{elementName}>{normalized value}</{elementName}>: a string value is
     * the element's text content; an object value is normalized through the serializer and built
     * into the element via buildDomFromArray (attributes + text content + any child elements),
     * interleaved with the other items in list order.
     *
     * These groups are mixed element-and-text, so an item named ChoiceGroupItem::TEXT_ELEMENT_NAME
     * is appended as a bare text node instead of an element — the text half of the content model
     * (CDA `<name>Example Clinic</name>`). It goes into this same fragment, so text and elements
     * interleave in list order and both survive; a scalar property alongside the group could not do
     * that, because the '#' key it would write is the one this fragment already occupies.
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

            $value = $item->value;

            if ($item->elementName === ChoiceGroupItem::TEXT_ELEMENT_NAME && is_string($value)) {
                if ($value !== '') {
                    $fragment->appendChild($document->createTextNode($value));
                }

                continue;
            }

            $element = $document->createElement($item->elementName);
            $this->fillChoiceGroupElement($document, $element, $value, $context);
            $fragment->appendChild($element);
        }

        return $fragment;
    }

    /**
     * Populate one element member of a choice-group fragment: a string value becomes the element's
     * text content; an object value is normalized through the serializer and built out via
     * buildDomFromArray (attributes + text content + any child elements).
     *
     * @param \DOMDocument         $document The fragment's owner document, used to create nodes
     * @param \DOMElement          $element  The member element to populate, already named
     * @param object|string        $value    The ChoiceGroupItem's value
     * @param array<string, mixed> $context  Serialization context for normalizing object values
     *
     * @return void The element is populated in place
     */
    private function fillChoiceGroupElement(
        \DOMDocument $document,
        \DOMElement $element,
        object|string $value,
        array $context,
    ): void {
        if (is_string($value)) {
            if ($value !== '') {
                $element->appendChild($document->createTextNode($value));
            }

            return;
        }

        $normalized = $this->normalizer !== null
            ? $this->normalizer->normalize($value, 'xml', $context)
            : $this->normalizeBasicValue($value, 'xml', $context);

        if (is_array($normalized)) {
            $this->buildDomFromArray($document, $element, $normalized);
        } elseif (is_scalar($normalized)) {
            $element->appendChild($document->createTextNode((string) $normalized));
        }
    }

    /**
     * Rebuild a transparent xml-choice-group property from the parent's source DOM element: walk its
     * child elements in document order and, for each whose local name matches a variant, append a
     * ChoiceGroupItem with the value denormalized to that variant's phpType. All element names map
     * to the one list property and append (no key-based dispatch) — this is the ordered read the
     * XmlEncoder-decoded array cannot provide.
     *
     * Inverse of the text half of the mixed content model (see buildChoiceGroupFragment): character
     * data between the element members becomes a ChoiceGroupItem::TEXT_ELEMENT_NAME item, so
     * `<name>Example Clinic</name>` reads back as text rather than as an empty name. Text is read
     * straight off the DOM node here and does NOT go through denormalizeChoiceGroupValue — that
     * method's builtin branch handles an element-wrapped string variant, which is a different case.
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

        $textIsAllowed = isset($variantByName[ChoiceGroupItem::TEXT_ELEMENT_NAME]);
        $hasElements   = $this->hasChildElement($element);

        $items = [];
        foreach ($element->childNodes as $child) {
            // \DOMCdataSection extends \DOMText, so CDATA sections are covered here too.
            if ($child instanceof \DOMText) {
                $text = $child->data;
                // Whitespace separating element members is XML layout, not content: keeping it would
                // turn a pretty-printed `<name>` into text/given/text/family/text. Whitespace is
                // preserved when it is the element's ONLY content, where it cannot be layout.
                if (!$textIsAllowed || $text === '' || ($hasElements && trim($text) === '')) {
                    continue;
                }

                $items[] = ChoiceGroupItem::text($text);

                continue;
            }

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
     * Build a complex-typed property from an element whose content was only character data.
     *
     * The decoded value is a bare string, so the object is denormalized from the text-content array
     * shape instead, with the element's own source DOM node threaded down. That node is what lets
     * the target recover the text: a type with a scalar `xmlText` property picks it up from the '#'
     * key, and a type whose content is a choice group rebuilds an ordered list carrying a
     * ChoiceGroupItem::TEXT_ELEMENT_NAME member (CDA `<name>Example Clinic</name>` -> an ON holding
     * one text member).
     *
     * @param string               $value         The element's character data
     * @param PropertyMetadata     $meta          Declared metadata for the property being filled
     * @param \ReflectionProperty  $property      The property being filled, for its declared type
     * @param string               $elementName   Local XML name, used to re-find the source child
     * @param \DOMElement|null     $sourceElement The parent's source element, when one was threaded in
     * @param array<string, mixed> $context       Denormalization context
     *
     * @return object|list<object>|null The datatype object, wrapped in a list for a repeating property
     */
    private function denormalizeCharacterDataElement(
        string $value,
        PropertyMetadata $meta,
        \ReflectionProperty $property,
        string $elementName,
        ?\DOMElement $sourceElement,
        array $context,
    ): object|array|null {
        $targetClass = $meta->phpItemClass ?? $this->getPropertyType($property);
        if ($targetClass === null || $this->isBuiltinType($targetClass) || $this->denormalizer === null) {
            return null;
        }

        $sourceChild = $sourceElement !== null
            ? ($this->childElementsByLocalName($sourceElement, $elementName)[0] ?? null)
            : null;
        if ($sourceChild !== null) {
            $context[self::SOURCE_ELEMENT_CONTEXT_KEY] = $sourceChild;
        }

        /** @var class-string $targetClass guarded by the isBuiltinType check above */
        $denormalized = $this->denormalizer->denormalize(['#' => $value], $targetClass, 'xml', $context);
        if (!is_object($denormalized)) {
            return null;
        }

        return $meta->isArray ? [$denormalized] : $denormalized;
    }

    /**
     * True when a property's declared metadata targets a datatype object rather than a raw scalar,
     * so an element that decoded to a bare string still has to be built into an object.
     *
     * `phpItemClass` covers a repeating complex property (`list<ON>`); `propertyKind: 'complex'`
     * covers a single-valued one. Primitives are excluded deliberately — they are handled by
     * denormalizePrimitiveProperty above and already carry their own text-content inverse.
     *
     * @param PropertyMetadata $meta The property's declared metadata
     *
     * @return bool Whether the decoded string needs re-shaping into a text-content array
     */
    private static function expectsComplexValue(PropertyMetadata $meta): bool
    {
        return $meta->phpItemClass !== null || $meta->propertyKind === 'complex';
    }

    /**
     * True when the element has at least one child element, i.e. its content is element-bearing and
     * any whitespace-only text between those children is layout rather than content.
     *
     * @param \DOMElement $element The choice-group parent whose children are being classified
     *
     * @return bool Whether any direct child is an element
     */
    private function hasChildElement(\DOMElement $element): bool
    {
        foreach ($element->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                return true;
            }
        }

        return false;
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

        // An element carrying no attributes and only text decodes to a bare string rather than an
        // array — and that is the shape published CDA uses for every name and address part
        // (`<family>Clinic</family>`). Returning the text here left the member a raw string while
        // its variant declares a datatype class, so `$item->value->xmlText` warned and yielded
        // null. Reshape it into the text-content form the target already reads, which is the same
        // inverse denormalizeCharacterDataElement applies one level up. The string is still the
        // fallback below when this does not produce an object.
        if (!is_array($decoded)) {
            $decoded = ['#' => $child->textContent];
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
