<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizer for FHIR complex type classes (Address, HumanName, etc.).
 *
 * This normalizer handles serialization and deserialization of FHIR complex types
 * following the official FHIR JSON specification. It supports nested object
 * serialization, choice element (value[x]) patterns, complex type extensions,
 * and polymorphic complex type resolution.
 *
 * @author Ardenexal
 */
class FHIRComplexTypeNormalizer extends AbstractFHIRNormalizer
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

        if (!$this->metadataExtractor->isComplexType($object)) {
            throw new InvalidArgumentException('Object is not a FHIR complex type');
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

        // Use type resolver for polymorphic complex type resolution
        $resolvedType = $this->typeResolver->resolveComplexType($data, $context);
        if ($resolvedType === null) {
            // Fall back to the provided type if resolver can't determine the type
            $resolvedType = $type;
        }

        // If the resolver returned an incompatible type (e.g. a short heuristic name like
        // 'FHIRPeriod' without a namespace), fall back to the expected type from the property
        // declaration. This avoids false-positive rejections when the type resolver guesses wrong.
        if ($resolvedType !== $type && !is_subclass_of($resolvedType, $type)) {
            $resolvedType = $type;
        }

        try {
            /** @var class-string $resolvedType */
            $reflection = new \ReflectionClass($resolvedType);
            $object     = $reflection->newInstanceWithoutConstructor();

            // Get property metadata for choice element mapping
            $metaMap = $this->getPropertyMetadataMap($object);

            // Set properties from the data
            foreach ($data as $elementName => $value) {
                // Skip underscore-prefixed extension properties, they're handled with their base property
                if (str_starts_with($elementName, '_')) {
                    continue;
                }

                // For XML: strip @ prefix from attribute keys (e.g. '@url' → 'url' for Extension.url).
                // XmlEncoder encodes XML attributes with @ prefix; FHIR models store them as plain properties.
                // Skip @value (primitive value attribute) and # (text node) — those are handled differently.
                if ($format === 'xml' && str_starts_with($elementName, '@') && $elementName !== '@value') {
                    $attrPropertyName = substr($elementName, 1);
                    if ($reflection->hasProperty($attrPropertyName)) {
                        $reflection->getProperty($attrPropertyName)->setValue($object, (string) $value);
                    }
                    continue;
                }

                // First, check if this is a choice element variant (e.g., 'valueQuantity' -> 'value')
                $choiceMapping = $this->findChoicePropertyByKey($metaMap, $elementName);
                if ($choiceMapping !== null) {
                    [$propertyName, $phpType, $fhirType] = $choiceMapping;

                    if ($reflection->hasProperty($propertyName)) {
                        $property = $reflection->getProperty($propertyName);

                        if ($this->denormalizer !== null && !$this->isBuiltinType($phpType)) {
                            $denormalizedValue = $this->denormalizer->denormalize($value, $phpType, $format, $context);
                        } else {
                            $rawValue          = $format === 'xml' ? $this->unwrapXmlValue($value, $phpType) : $value;
                            $denormalizedValue = ($fhirType === 'decimal' || $fhirType === 'http://hl7.org/fhirpath/System.Decimal') && is_numeric($rawValue)
                                ? (string) $rawValue
                                : $rawValue;
                        }

                        $property->setValue($object, $denormalizedValue);
                        continue;
                    }
                }

                // Standard property mapping
                if ($reflection->hasProperty($elementName)) {
                    $property = $reflection->getProperty($elementName);
                    $meta     = $metaMap[$elementName] ?? null;

                    if ($this->denormalizer !== null) {
                        if ($format === 'xml' && $meta !== null && $meta->fhirType === 'xhtml' && is_array($value)) {
                            // Xhtml special case: XmlEncoder decoded the <div> subtree as a nested array.
                            // Convert the array back to an XML string using DOMDocument (which correctly
                            // handles repeated sibling elements), then store the string in XhtmlPrimitive.value.
                            $xhtmlClass = $this->getFirstNonBuiltinTypeFromProperty($property);
                            if ($xhtmlClass !== null) {
                                /** @var class-string $xhtmlClass */
                                $xhtmlRefl     = new \ReflectionClass($xhtmlClass);
                                $xhtmlInstance = $xhtmlRefl->newInstanceWithoutConstructor();
                                if ($xhtmlRefl->hasProperty('value')) {
                                    $xmlString = $this->encodeXhtmlToString($value, 'div');
                                    $xhtmlRefl->getProperty('value')->setValue($xhtmlInstance, $xmlString);
                                }
                                $denormalizedValue = $xhtmlInstance;
                            } else {
                                $denormalizedValue = null;
                            }
                        } elseif ($meta !== null && $meta->propertyKind === 'primitive') {
                            // Always produce Primitive objects so that _property extension data
                            // can be attached to the instances in the second pass below.
                            $denormalizedValue = $this->denormalizePrimitiveProperty($meta, $property, $reflection, $value, $format, $context, $metaMap);
                        } else {
                            $phpItemClass = $meta?->phpItemClass;
                            if ($phpItemClass !== null && is_array($value)) {
                                // Array of typed complex/backbone items: denormalize each element to the typed class.
                                if ($format === 'xml') {
                                    $items = $this->unwrapXmlValue($value, 'array');
                                    if (is_array($items) && !array_is_list($items)) {
                                        $items = [$items];
                                    }
                                    $denormalizedValue = [];
                                    foreach ((array) $items as $item) {
                                        /** @var class-string $phpItemClass */
                                        $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, $format, $context);
                                    }
                                } else {
                                    $denormalizedValue = [];
                                    foreach ($value as $item) {
                                        /** @var class-string $phpItemClass */
                                        $denormalizedValue[] = $this->denormalizer->denormalize($item, $phpItemClass, $format, $context);
                                    }
                                }
                            } else {
                                $propertyType = $this->getPropertyType($property);
                                if ($propertyType !== null && !$this->isBuiltinType($propertyType)) {
                                    $denormalizedValue = $this->denormalizer->denormalize($value, $propertyType, $format, $context);
                                } else {
                                    // For XML, Symfony XmlEncoder wraps primitive values as ['@value' => '...', '#' => ''].
                                    // Unwrap before assigning to string/union-typed properties.
                                    if ($format === 'xml') {
                                        $denormalizedValue = $this->unwrapXmlValue($value, $propertyType);
                                        // If unwrapping left an array (because the XML element had child elements,
                                        // e.g. an inline extension), extract just the scalar @value so it can be
                                        // assigned to union-typed properties like StringPrimitive|string|null.
                                        if (is_array($denormalizedValue) && isset($denormalizedValue['@value'])) {
                                            $denormalizedValue = $denormalizedValue['@value'];
                                        }
                                    } else {
                                        $denormalizedValue = $value;
                                    }
                                }
                            }
                        }
                    } else {
                        $denormalizedValue = $this->denormalizeBasicValue($value, $format, $context);
                    }

                    $property->setValue($object, $denormalizedValue);
                }
            }

            // Apply _property extension data to already-denormalized primitive properties.
            $this->applyPrimitiveExtensions($reflection, $object, $data, $metaMap, $format, $context);

            return $object;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $resolvedType, $e->getMessage()), 0, $e);
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

        // Check if the type is a FHIR complex type class
        try {
            /** @var class-string $type */
            $reflection = new \ReflectionClass($type);
            $attributes = $reflection->getAttributes(FHIRComplexType::class);

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
        // This normalizer supports any class with the FHIRComplexType attribute
        return ['object' => true];
    }

    /**
     * Encode an XmlEncoder-decoded XHTML array back to an XML string using DOMDocument.
     *
     * XmlEncoder::encode cannot reliably round-trip repeated sibling elements
     * (e.g. two <p> tags) — it wraps them in <item> elements. DOMDocument handles
     * this correctly and is used here instead.
     *
     * The $elementName parameter names the root element (typically 'div' for FHIR xhtml).
     * The $data array uses XmlEncoder conventions: '@key' for attributes, '#' for text nodes,
     * and list arrays for repeated sibling elements.
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
     * Recursively populate a DOMElement from an XmlEncoder-style data array.
     *
     * @param array<array-key, mixed> $data
     */
    private function buildDomFromArray(\DOMDocument $dom, \DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                continue; // skip numeric keys (handled by list branch in parent call)
            }

            // Skip XmlEncoder meta-keys that are not mappable to DOM nodes
            if (str_starts_with($key, '#') && $key !== '#' && $key !== '#text') {
                // '#comment', '#document', etc. — skip
                continue;
            }

            if (str_starts_with($key, '@')) {
                // XML attribute (e.g. @xmlns, @href)
                $parent->setAttribute(substr($key, 1), (string) $value);
            } elseif ($key === '#' || $key === '#text') {
                // Text node content.
                // '#' = single text node; '#text' = multiple text nodes in mixed content
                // (XmlEncoder uses #text when text and element nodes coexist as siblings).
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
                // Multiple sibling elements with the same tag name (e.g. two <p> elements)
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
                // Single child element with sub-structure
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                $this->buildDomFromArray($dom, $child, $value);
            } else {
                // Single child element with text content (or self-closing like <br/>)
                $child = $dom->createElement($key);
                $parent->appendChild($child);
                if ($value !== '' && $value !== null) {
                    $child->appendChild($dom->createTextNode((string) $value));
                }
            }
        }
    }

    /**
     * Decode an XHTML XML element string back to the array format XmlEncoder expects.
     *
     * Used when XhtmlPrimitive.value holds a raw XML string (e.g. from JSON deserialization
     * where FHIR stores xhtml as a string). Parses the string and returns the content of the
     * root element as an associative array (attributes keyed with @ prefix, child elements
     * keyed by tag name) so that XmlEncoder can re-emit it as proper nested XML elements.
     *
     * The decoded array is post-processed so that lists of scalar values are converted to
     * lists of text-node objects (e.g. `'p' => ['First', 'Second']` becomes
     * `'p' => [['#' => 'First'], ['#' => 'Second']]`). This prevents XmlEncoder from
     * wrapping repeated sibling elements in `<item>` tags when re-encoding.
     *
     * @return array<string, mixed>
     */
    private function decodeXhtmlToArray(string $xmlString): array
    {
        $xmlEncoder = new XmlEncoder();
        $decoded    = $xmlEncoder->decode($xmlString, 'xml');

        if (!is_array($decoded)) {
            return [];
        }

        return $this->transformXhtmlArrayForReencoding($decoded);
    }

    /**
     * Recursively transform an XmlEncoder-decoded XHTML array so it can be re-encoded correctly.
     *
     * XmlEncoder incorrectly wraps repeated sibling elements in <item> tags when the value is
     * a list of scalars (e.g. `'p' => ['First', 'Second']`). Converting each scalar to an
     * associative array with a '#' text-node key fixes this: XmlEncoder then emits repeated
     * `<p>` elements correctly.
     *
     * Also maps `#text` (XmlEncoder's mixed-content key) to `#` (standard text-node key).
     *
     * @param array<string, mixed> $data
     *
     * @return array<array-key, mixed>
     */
    private function transformXhtmlArrayForReencoding(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            // #text is XmlEncoder's key for multiple text nodes in mixed content. Map to '#'.
            if ($key === '#text') {
                $combined        = is_array($value) ? implode('', $value) : (string) $value;
                $result['#']     = ($result['#'] ?? '') . $combined;
                continue;
            }

            if (is_array($value) && array_is_list($value)) {
                // List of items: wrap plain scalars in ['#' => scalar] so XmlEncoder
                // produces repeated <tag>text</tag> elements instead of <tag><item>...</item></tag>.
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

    /**
     * Check if a property name represents a choice element (value[x] pattern).
     */
    private function isChoiceElement(string $propertyName): bool
    {
        // Choice elements typically start with 'value' followed by a type suffix
        return str_starts_with($propertyName, 'value') && strlen($propertyName) > 5;
    }

    /**
     * Normalize a choice element with proper type suffix handling.
     *
     * @param array<string, mixed> $context
     */
    private function normalizeChoiceElement(string $propertyName, mixed $value, ?string $format, array $context): mixed
    {
        // For choice elements, we need to include the type suffix in the property name
        // The property name already contains the type suffix (e.g., valueString, valueInteger)

        if ($this->normalizer !== null) {
            return $this->normalizer->normalize($value, $format, $context);
        }

        return $this->normalizeBasicValue($value, $format, $context);
    }

    /**
     * Normalize complex type for JSON format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForJSON(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the object
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

            // Handle choice elements: metadata-driven (generated classes) or legacy heuristic
            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                        || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
                // Metadata-driven: resolve concrete JSON key and kind from variant map
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey, $resolvedFhirType] = $choiceMatch;
                    $jsonKey = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                        if ($normalizedValue !== null) {
                            $data[$jsonKey] = $normalizedValue['value'];
                            if (isset($normalizedValue['extensions'])) {
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
                // Legacy heuristic fallback for non-generated classes
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue;
                }
                continue;
            }

            // Handle extensions with underscore notation for primitives
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'json', $context);
                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue['value'];
                    if (isset($normalizedValue['extensions'])) {
                        $data['_' . $jsonKey] = ['extension' => $normalizedValue['extensions']];
                    }
                }
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
                if ($this->normalizer !== null) {
                    $normalizedValue = $this->normalizer->normalize($value, 'json', $context);
                } else {
                    $normalizedValue = $this->normalizeBasicValue($value, 'json', $context);
                }

                $normalizedValue = $this->castNumericScalarForJson($normalizedValue, $meta);

                if ($normalizedValue !== null) {
                    $data[$jsonKey] = $normalizedValue;
                }
            }
        }

        return $data;
    }

    /**
     * Normalize complex type for XML format.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, array $context): array
    {
        $data    = [];
        $metaMap = $this->getPropertyMetadataMap($object);

        // Normalize all properties of the object
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

            // Handle choice elements: metadata-driven (generated classes) or legacy heuristic
            $isChoice = ($meta !== null && $meta->isChoice && !empty($meta->variants))
                        || ($meta === null && $this->isChoiceElement($propertyName));

            if ($isChoice && $meta !== null && !empty($meta->variants)) {
                // Metadata-driven: resolve concrete XML key and kind from variant map
                $choiceMatch = $this->resolveChoiceVariant($value, $meta->variants);
                if ($choiceMatch !== null) {
                    [$resolvedKind, $resolvedKey] = $choiceMatch;
                    $xmlKey = $resolvedKey;
                    if ($resolvedKind === 'primitive' && $this->isPrimitiveWithExtensions($value)) {
                        $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
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
                // Legacy heuristic fallback for non-generated classes
                $normalizedValue = $this->normalizeChoiceElement($propertyName, $value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
                continue;
            }

            // Xhtml special case: XhtmlPrimitive.value holds either:
            //   - a raw XmlEncoder array (stored during XML deserialization) — pass through as-is, or
            //   - an XML string (stored during JSON deserialization) — decode back to array first.
            // Either way, XmlEncoder receives the nested array and emits proper XHTML child elements.
            if ($meta !== null && $meta->fhirType === 'xhtml' && is_object($value)) {
                $xhtmlReflection = new \ReflectionClass($value);
                if ($xhtmlReflection->hasProperty('value')) {
                    $rawXhtml = $xhtmlReflection->getProperty('value')->getValue($value);
                    if (is_array($rawXhtml)) {
                        $xhtmlArray           = $rawXhtml;
                        $xhtmlArray['@xmlns'] = 'http://www.w3.org/1999/xhtml';
                        $data[$xmlKey]        = $xhtmlArray;
                        continue;
                    } elseif (is_string($rawXhtml)) {
                        // If the string is XML (starts with '<'), decode back to XmlEncoder array.
                        // Otherwise it's plain text — emit as XmlEncoder text-node content.
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

            // xmlAttr properties: emit as XML attribute on the parent element
            if ($meta !== null && $meta->xmlSerializedName !== null && is_scalar($value)) {
                $data[$meta->xmlSerializedName] = is_bool($value)
                    ? ($value ? 'true' : 'false')
                    : (string) $value;
                continue;
            }

            // Handle primitive extensions for XML (no underscore notation)
            if ($this->isPrimitiveWithExtensions($value)) {
                $normalizedValue = $this->normalizePrimitiveWithExtensions($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    $data[$xmlKey] = $normalizedValue;
                }
            } elseif (is_scalar($value)) {
                // FHIR XML: scalar values are child elements with a @value attribute,
                // e.g. <period value="21"/>, not XML attributes on the parent element.
                $data[$xmlKey] = ['@value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value];
            } else {
                // Use the injected normalizer if available, otherwise handle basic types
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
