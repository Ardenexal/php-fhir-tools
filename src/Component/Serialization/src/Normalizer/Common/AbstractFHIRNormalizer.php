<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyVariantMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Abstract base class for FHIR normalizers providing shared utility methods.
 *
 * @author Ardenexal
 */
abstract class AbstractFHIRNormalizer implements FHIRNormalizerInterface, SerializerAwareInterface
{
    protected ?NormalizerInterface $normalizer = null;

    protected ?DenormalizerInterface $denormalizer = null;

    /** @var array<string, FHIRPrimitive|null> */
    private array $primitiveAttributeCache = [];

    /** @var array<class-string, \ReflectionClass<object>> */
    private static array $reflClassCache = [];

    /** @var array<class-string, list<\ReflectionProperty>> */
    private static array $reflPropsCache = [];

    /** @var array<class-string, array<string, \ReflectionProperty>> */
    private static array $reflPropByNameCache = [];

    /** @var array<class-string, array<string, array{0: string, 1: string, 2: string}>> */
    private static array $choiceKeyIndexCache = [];

    private readonly string $baseExtensionClass;

    private readonly string $fhirVersion;

    public function __construct(
        protected readonly FHIRMetadataExtractorInterface $metadataExtractor,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        string $fhirVersion = 'R4',
        protected ?FHIRIGTypeRegistry $igTypeRegistry = null,
    ) {
        $this->normalizer         = $normalizer;
        $this->denormalizer       = $denormalizer;
        $this->fhirVersion        = $fhirVersion;
        $this->baseExtensionClass = FhirVersion::from($fhirVersion)->extensionFqcn();
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($serializer instanceof NormalizerInterface) {
            $this->normalizer = $serializer;
        }

        if ($serializer instanceof DenormalizerInterface) {
            $this->denormalizer = $serializer;
        }
    }

    // -------------------------------------------------------------------------
    // Reflection cache helpers
    // -------------------------------------------------------------------------

    /**
     * @param string|object $subject class-string or object instance
     *
     * @return \ReflectionClass<object>
     */
    protected static function reflClass(string|object $subject): \ReflectionClass
    {
        /** @var class-string $name */
        $name = is_object($subject) ? get_class($subject) : $subject;

        return self::$reflClassCache[$name] ??= new \ReflectionClass($name);
    }

    /**
     * @param string|object $subject class-string or object instance
     *
     * @return list<\ReflectionProperty>
     */
    protected static function reflPublicProps(string|object $subject): array
    {
        /** @var class-string $name */
        $name = is_object($subject) ? get_class($subject) : $subject;

        return self::$reflPropsCache[$name]
            ??= self::reflClass($name)->getProperties(\ReflectionProperty::IS_PUBLIC);
    }

    /**
     * @param string|object $subject class-string or object instance
     */
    protected static function reflProp(string|object $subject, string $propName): ?\ReflectionProperty
    {
        /** @var class-string $name */
        $name = is_object($subject) ? get_class($subject) : $subject;

        if (!array_key_exists($name, self::$reflPropByNameCache)) {
            $map = [];
            foreach (self::reflPublicProps($name) as $p) {
                $map[$p->getName()] = $p;
            }
            self::$reflPropByNameCache[$name] = $map;
        }

        return self::$reflPropByNameCache[$name][$propName] ?? null;
    }

    // -------------------------------------------------------------------------
    // Primitive detection
    // -------------------------------------------------------------------------

    protected function isPrimitiveWithExtensions(mixed $value): bool
    {
        if (!is_object($value)) {
            return false;
        }

        return $this->metadataExtractor->isPrimitiveType($value);
    }

    /**
     * Walk the class hierarchy and return the first FHIRPrimitive attribute found, or null.
     */
    protected function findFHIRPrimitiveAttribute(string $type): ?FHIRPrimitive
    {
        if (array_key_exists($type, $this->primitiveAttributeCache)) {
            return $this->primitiveAttributeCache[$type];
        }

        try {
            $reflection = self::reflClass($type);

            do {
                $attributes = $reflection->getAttributes(FHIRPrimitive::class);
                if (!empty($attributes)) {
                    /** @var FHIRPrimitive */
                    return $this->primitiveAttributeCache[$type] = $attributes[0]->newInstance();
                }

                $reflection = $reflection->getParentClass();
            } while ($reflection !== false);

            return $this->primitiveAttributeCache[$type] = null;
        } catch (\ReflectionException) {
            return $this->primitiveAttributeCache[$type] = null;
        }
    }

    protected function hasFHIRPrimitiveAttribute(string $type): bool
    {
        return $this->findFHIRPrimitiveAttribute($type) !== null;
    }

    // -------------------------------------------------------------------------
    // Normalization helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    protected function normalizePrimitiveWithExtensions(mixed $value, ?string $format, array $context, bool $includeExtensions = true): ?array
    {
        if (!is_object($value)) {
            return null;
        }

        $result        = [];
        $valueProperty = self::reflProp($value, 'value');

        if ($valueProperty !== null && $valueProperty->isInitialized($value)) {
            $raw = $valueProperty->getValue($value);

            if ($raw instanceof FHIRTemporalValue) {
                $raw = (string) $raw;
            }

            // XmlEncoder casts PHP booleans to int; FHIR XML requires "true"/"false".
            if (is_bool($raw) && $format === 'xml') {
                $raw = $raw ? 'true' : 'false';
            }

            if (is_string($raw) && is_numeric($raw) && $format !== 'xml') {
                if ($this->isDecimalPrimitive($value)) {
                    $raw = (float) $raw;
                }
            }

            if ($raw !== null) {
                $valueKey          = ($format === 'xml') ? '@value' : 'value';
                $result[$valueKey] = $raw;
            }
        }

        if ($includeExtensions) {
            $extensionProperty = self::reflProp($value, 'extension');
            if ($extensionProperty !== null) {
                $extensions = $extensionProperty->isInitialized($value) ? $extensionProperty->getValue($value) : null;
                if ($extensions !== null && !empty($extensions)) {
                    if ($this->normalizer !== null) {
                        $normalizedExtensions = $this->normalizer->normalize($extensions, $format, $context);
                    } else {
                        $normalizedExtensions = $extensions;
                    }
                    $extensionKey          = ($format === 'xml') ? 'extension' : 'extensions';
                    $result[$extensionKey] = $normalizedExtensions;
                }
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Normalize each extension object in the array via the injected normalizer.
     *
     * @param array<string, mixed> $context
     *
     * @return list<mixed>|null
     */
    protected function normalizeExtensions(mixed $extensions, ?string $format, array $context): ?array
    {
        if (!is_array($extensions) || empty($extensions)) {
            return null;
        }

        $result = [];
        foreach ($extensions as $extension) {
            $normalizedExtension = $this->normalizer !== null
                ? $this->normalizer->normalize($extension, $format, $context)
                : $this->normalizeBasicValue($extension, $format, $context);

            if ($normalizedExtension !== null) {
                $result[] = $normalizedExtension;
            }
        }

        return count($result) === 0 ? null : $result;
    }

    /**
     * Wrap a polymorphic resource property for XML output.
     *
     * FHIR XML requires polymorphic resource properties to be wrapped with the
     * resource type as the element name, e.g. <contained><Medication>...</Medication></contained>.
     *
     * @param array<string, mixed> $context
     *
     * @return array<mixed>|null
     */
    protected function normalizePolymorphicResourcesXml(mixed $value, PropertyMetadata $meta, array $context): mixed
    {
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
                    ? $this->normalizer->normalize($item, 'xml', $context)
                    : $this->normalizeBasicValue($item, 'xml', $context);
                if ($normalizedItem !== null) {
                    $wrappedItems[] = [$itemResourceType => $normalizedItem];
                }
            }

            return !empty($wrappedItems) ? $wrappedItems : null;
        }

        if (!$meta->isArray && is_object($value)) {
            $itemResourceType = $this->metadataExtractor->extractResourceType($value);
            if ($itemResourceType !== null) {
                $normalizedValue = $this->normalizer !== null
                    ? $this->normalizer->normalize($value, 'xml', $context)
                    : $this->normalizeBasicValue($value, 'xml', $context);
                if ($normalizedValue !== null) {
                    return [$itemResourceType => $normalizedValue];
                }
            }
        }

        return null;
    }

    /**
     * Wrap a scalar value as an XML element with a @value attribute.
     *
     * XmlEncoder emits ['@value' => 'x'] as <element value="x"/>.
     *
     * @return array<string, mixed>
     */
    protected function wrapScalarForXml(mixed $value): array
    {
        return ['@value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value];
    }

    /**
     * @param array<string, mixed> $context
     */
    protected function normalizeBasicValue(mixed $value, ?string $format, array $context): mixed
    {
        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $normalizedItem = $this->normalizeBasicValue($item, $format, $context);
                if ($normalizedItem !== null) {
                    $result[$key] = $normalizedItem;
                }
            }

            return $result;
        }

        if (is_object($value)) {
            $result     = [];
            $properties = self::reflPublicProps($value);

            foreach ($properties as $property) {
                if (!$property->isInitialized($value)) {
                    continue;
                }
                $propertyValue = $property->getValue($value);
                if ($propertyValue !== null) {
                    $result[$property->getName()] = $this->normalizeBasicValue($propertyValue, $format, $context);
                }
            }

            return $result;
        }

        return $value;
    }

    // -------------------------------------------------------------------------
    // Denormalization helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<string, mixed> $context
     */
    protected function denormalizeBasicValue(mixed $value, ?string $format, array $context): mixed
    {
        return $value;
    }

    /**
     * @param \ReflectionClass<object>        $reflection
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function denormalizePrimitiveProperty(
        PropertyMetadata $meta,
        \ReflectionProperty $property,
        \ReflectionClass $reflection,
        mixed $value,
        ?string $format,
        array $context,
        array $metaMap,
    ): mixed {
        if ($this->denormalizer === null) {
            return $value;
        }

        if ($meta->isArray && is_array($value)) {
            $primitiveClass = $this->resolvePrimitiveArrayItemClass($reflection, $meta->fhirType, $metaMap);
            if ($primitiveClass === null) {
                return $value;
            }

            $result = [];
            foreach ($value as $item) {
                $result[] = $this->denormalizer->denormalize($item, $primitiveClass, $format, $context);
            }

            return $result;
        }

        if (!$meta->isArray) {
            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($property);
            if ($primitiveClass === null) {
                return $value;
            }

            return $this->denormalizer->denormalize($value, $primitiveClass, $format, $context);
        }

        return $value;
    }

    /**
     * Second-pass: attach underscore-prefixed extension data to already-denormalized primitive properties.
     *
     * @param \ReflectionClass<object>        $reflection
     * @param array<string, mixed>            $data
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function applyPrimitiveExtensions(
        \ReflectionClass $reflection,
        object $object,
        array $data,
        array $metaMap,
        ?string $format,
        array $context,
    ): void {
        foreach ($data as $elementName => $extData) {
            if (!str_starts_with($elementName, '_')) {
                continue;
            }

            $baseName = substr($elementName, 1);
            $meta     = $metaMap[$baseName] ?? null;

            if ($meta === null || $meta->propertyKind !== 'primitive' || !$reflection->hasProperty($baseName)) {
                continue;
            }

            $property = $reflection->getProperty($baseName);

            if (!$meta->isArray) {
                if (!is_array($extData) || !isset($extData['extension']) || !is_array($extData['extension'])) {
                    continue;
                }

                $current = $property->isInitialized($object) ? $property->getValue($object) : null;

                if (!is_object($current)) {
                    $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($property);
                    if ($primitiveClass === null || $this->denormalizer === null) {
                        continue;
                    }

                    $rawValue = is_scalar($current) ? $current : null;
                    $current  = $this->denormalizer->denormalize($rawValue, $primitiveClass, $format, $context);
                    $property->setValue($object, $current);
                }

                $extensionProp = self::reflProp($current, 'extension');
                if ($extensionProp !== null) {
                    $denormalizedExtensions = $this->denormalizeExtensionArray($extData['extension'], $format, $context);
                    $extensionProp->setValue($current, $denormalizedExtensions);
                }
            } else {
                if (!is_array($extData)) {
                    continue;
                }

                $currentArray = $property->isInitialized($object) ? $property->getValue($object) : [];
                if (!is_array($currentArray)) {
                    continue;
                }

                $maxLen = max(count($currentArray), count($extData));

                for ($i = 0; $i < $maxLen; ++$i) {
                    $extEntry         = $extData[$i] ?? null;
                    $hasExtensionData = is_array($extEntry) && isset($extEntry['extension']) && is_array($extEntry['extension']);

                    if (!isset($currentArray[$i]) || !is_object($currentArray[$i])) {
                        if (!$hasExtensionData) {
                            continue;
                        }

                        $primitiveClass = $this->resolvePrimitiveArrayItemClass($reflection, $meta->fhirType, $metaMap);
                        if ($primitiveClass === null || $this->denormalizer === null) {
                            continue;
                        }

                        $currentArray[$i] = $this->denormalizer->denormalize(null, $primitiveClass, $format, $context);
                    }

                    $extensionProp = self::reflProp($currentArray[$i], 'extension');

                    if ($extensionProp === null) {
                        continue;
                    }

                    if ($hasExtensionData) {
                        $denormalizedExtensions = $this->denormalizeExtensionArray($extEntry['extension'], $format, $context);
                        $extensionProp->setValue($currentArray[$i], $denormalizedExtensions);
                    } elseif (!$extensionProp->isInitialized($currentArray[$i])) {
                        $extensionProp->setValue($currentArray[$i], []);
                    }
                }

                $property->setValue($object, $currentArray);
            }
        }
    }

    /**
     * Denormalize an extension array, using the IG registry for typed extension resolution.
     *
     * @param array<mixed>         $extensionData
     * @param array<string, mixed> $context
     *
     * @return array<FHIRExtensionInterface>
     */
    protected function denormalizeExtensionArray(array $extensionData, ?string $format, array $context): array
    {
        if ($this->denormalizer === null) {
            return $extensionData;
        }

        $denormalizedExtensions = [];

        foreach ($extensionData as $extension) {
            if (!is_array($extension)) {
                $denormalizedExtensions[] = $extension;
                continue;
            }

            $targetClass = $this->baseExtensionClass;
            // XmlEncoder decodes XML attributes with an '@' prefix, so 'url' becomes '@url'.
            // Check both so JSON and XML resolve typed extensions consistently.
            $url = $extension['url'] ?? $extension['@url'] ?? null;
            if ($this->igTypeRegistry !== null && is_string($url)) {
                $targetClass = $this->igTypeRegistry->resolveExtensionClass($url, $this->fhirVersion) ?? $this->baseExtensionClass;
            }

            $denormalizedExtensions[] = $this->denormalizer->denormalize($extension, $targetClass, $format, $context);
        }

        return $denormalizedExtensions;
    }

    /**
     * Handle unknown properties according to the configured policy.
     */
    protected function handleUnknownProperty(string $propertyName, mixed $value, string $policy, object $object, ?string $elementPath = null): void
    {
        switch ($policy) {
            case FHIRSerializationContext::UNKNOWN_POLICY_ERROR:
                throw FHIRSerializationException::unknownElementError($propertyName, $policy, $elementPath, ['property_value' => $value]);

            case FHIRSerializationContext::UNKNOWN_POLICY_PRESERVE:
                if (property_exists($object, $propertyName)) {
                    $object->{$propertyName} = $value;
                }
                break;

            case FHIRSerializationContext::UNKNOWN_POLICY_IGNORE:
            default:
                break;
        }
    }

    // -------------------------------------------------------------------------
    // Primitive value validation (used by FHIRPrimitiveType{Json,Xml}Normalizer)
    // -------------------------------------------------------------------------

    /**
     * Create a primitive instance with value and optional extensions.
     *
     * @param array<string, mixed> $context
     */
    protected function createPrimitiveInstance(string $type, mixed $value, mixed $extensions, ?string $format = null, array $context = []): mixed
    {
        try {
            $reflection = self::reflClass($type);

            $validatedValue = $this->validateAndConvertValue($value, $type);

            $instance = $reflection->newInstanceWithoutConstructor();

            $valueProp = self::reflProp($type, 'value');
            if ($valueProp !== null) {
                $valueProp->setValue($instance, $validatedValue);
            }

            if ($extensions !== null) {
                $extensionProp = self::reflProp($type, 'extension');
                if ($extensionProp !== null) {
                    if ($this->denormalizer !== null && is_array($extensions)) {
                        $extensionProp->setValue($instance, $this->denormalizeExtensionArray($extensions, $format, $context));
                    } else {
                        $extensionProp->setValue($instance, $extensions);
                    }
                }
            }

            return $instance;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
        }
    }

    protected function validateAndConvertValue(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        $primitiveAttribute = $this->findFHIRPrimitiveAttribute($type);

        if ($primitiveAttribute === null) {
            return $value;
        }

        return match ($primitiveAttribute->primitiveType) {
            'string', 'code', 'uri', 'url', 'canonical', 'base64Binary',
            'oid', 'id', 'uuid', 'markdown', 'xhtml'             => $this->validateString($value),
            'date'                                               => $this->parseTemporalValue($value, FHIRDate::class),
            'time'                                               => $this->parseTemporalValue($value, FHIRTime::class),
            'dateTime'                                           => $this->parseTemporalValue($value, FHIRDateTime::class),
            'instant'                                            => $this->parseTemporalValue($value, FHIRInstant::class),
            'integer', 'positiveInt', 'unsignedInt'              => $this->validateInteger($value),
            'decimal'                                            => $this->validateDecimal($value),
            'boolean'                                            => $this->validateBoolean($value),
            default                                              => $value,
        };
    }

    protected function validateString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new NotNormalizableValueException(sprintf('Expected string value, got %s', gettype($value)));
    }

    protected function validateInteger(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && is_numeric($value)) {
            $intValue = (int) $value;
            if ((string) $intValue === $value) {
                return $intValue;
            }
        }

        if (is_float($value) && $value === floor($value)) {
            return (int) $value;
        }

        throw new NotNormalizableValueException(sprintf('Expected integer value, got %s', gettype($value)));
    }

    /**
     * @return numeric-string|null
     */
    protected function validateDecimal(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value)) {
            $str = rtrim(sprintf('%.14F', $value), '0');
            $str = str_ends_with($str, '.') ? $str . '0' : $str;
            assert(is_numeric($str));

            return $str;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return $value;
        }

        throw new NotNormalizableValueException(sprintf('Expected decimal value, got %s', gettype($value)));
    }

    protected function validateBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') {
                return true;
            }
            if ($lower === 'false') {
                return false;
            }
        }

        if (is_int($value)) {
            return $value !== 0;
        }

        throw new NotNormalizableValueException(sprintf('Expected boolean value, got %s', gettype($value)));
    }

    /**
     * @param class-string<FHIRTemporalValue> $class
     */
    protected function parseTemporalValue(mixed $value, string $class): ?FHIRTemporalValue
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof $class) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (is_string($value)) {
            try {
                return $class::parse($value);
            } catch (\Throwable $e) {
                throw new NotNormalizableValueException(sprintf('Expected %s string, got invalid value: %s', $class, $value), 0, $e);
            }
        }

        throw new NotNormalizableValueException(sprintf('Expected %s value, got %s', $class, gettype($value)));
    }

    // -------------------------------------------------------------------------
    // XML helpers
    // -------------------------------------------------------------------------

    /**
     * Extract the resource element name from XML data for polymorphic resource properties.
     *
     * In FHIR XML, polymorphic resource properties contain a nested element whose name
     * indicates the resource type: <resource><Patient>...</Patient></resource>
     */
    protected function extractResourceElementName(mixed $value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        foreach ($value as $key => $data) {
            if (!str_starts_with($key, '@') && !str_starts_with($key, '#')) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Unwrap a FHIR XML value encoded as ['@value' => '...', '#' => ''] by Symfony's XmlEncoder.
     */
    protected function unwrapXmlValue(mixed $value, ?string $propertyType = null): mixed
    {
        if (is_array($value) && array_key_exists('@value', $value)) {
            $otherKeys = array_diff(array_keys($value), ['@value', '#']);
            if (empty($otherKeys)) {
                $value = $value['@value'];
            }
        }

        if ($propertyType === 'bool' && is_string($value)) {
            return $value === 'true';
        }

        if ($propertyType === 'int' && is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        if ($propertyType === 'float' && is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        if ($propertyType === 'array' && !is_array($value)) {
            $value = [$value];
        }

        if ($propertyType === 'array' && is_array($value)) {
            $value = $this->stripXmlMetaKeys($value);
        }

        return $value;
    }

    /**
     * @param array<mixed> $data
     *
     * @return array<mixed>
     */
    protected function stripXmlMetaKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && str_starts_with($key, '#')) {
                continue;
            }
            $result[$key] = is_array($value) ? $this->stripXmlMetaKeys($value) : $value;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function cleanXmlArtifacts(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            // @phpstan-ignore-next-line function.alreadyNarrowedType
            if (!is_string($key)) {
                $cleaned[$key] = is_array($value) ? $this->cleanXmlArtifacts($value) : $value;
                continue;
            }

            $cleanKey = str_starts_with($key, '@') ? substr($key, 1) : $key;

            if ($cleanKey === '#') {
                continue;
            }

            $cleaned[$cleanKey] = is_array($value) ? $this->cleanXmlArtifacts($value) : $value;
        }

        return $cleaned;
    }

    // -------------------------------------------------------------------------
    // Metadata helpers
    // -------------------------------------------------------------------------

    /**
     * @return array<string, PropertyMetadata>
     */
    protected function getPropertyMetadataMap(object $object): array
    {
        return $this->metadataExtractor->getPropertyMetadataProvider()->getPropertyMetadata(get_class($object));
    }

    /**
     * @param list<PropertyVariantMetadata> $variants
     *
     * @return array{0: string, 1: string, 2: string}|null [propertyKind, jsonKey, fhirType] or null
     */
    protected function resolveChoiceVariant(mixed $value, array $variants): ?array
    {
        /** @var array<string, string> */
        static $phpToGettype = ['bool' => 'boolean', 'int' => 'integer', 'float' => 'double', 'string' => 'string'];

        foreach ($variants as $variant) {
            if ($variant->isBuiltin) {
                if (gettype($value) === ($phpToGettype[$variant->phpType] ?? '')) {
                    return [$variant->propertyKind, $variant->jsonKey, $variant->fhirType];
                }
            } elseif (is_object($value) && $value instanceof $variant->phpType) {
                return [$variant->propertyKind, $variant->jsonKey, $variant->fhirType];
            }
        }

        return null;
    }

    /**
     * @param array<string, PropertyMetadata> $metaMap
     * @param string|null                     $className pass the FQCN to enable O(1) lookup via per-class index cache
     *
     * @return array{0: string, 1: string, 2: string}|null [propertyName, phpType, fhirType] or null
     */
    protected function findChoicePropertyByKey(array $metaMap, string $elementKey, ?string $className = null): ?array
    {
        if ($className !== null) {
            /** @var class-string $className */
            if (!isset(self::$choiceKeyIndexCache[$className])) {
                $index = [];
                foreach ($metaMap as $propertyName => $meta) {
                    if ($meta->isChoice && !empty($meta->variants)) {
                        foreach ($meta->variants as $variant) {
                            $index[$variant->jsonKey] = [$propertyName, $variant->phpType, $variant->fhirType];
                        }
                    }
                }
                self::$choiceKeyIndexCache[$className] = $index;
            }

            return self::$choiceKeyIndexCache[$className][$elementKey] ?? null;
        }

        foreach ($metaMap as $propertyName => $meta) {
            if ($meta->isChoice && !empty($meta->variants)) {
                foreach ($meta->variants as $variant) {
                    if ($variant->jsonKey === $elementKey) {
                        return [$propertyName, $variant->phpType, $variant->fhirType];
                    }
                }
            }
        }

        return null;
    }

    protected function isChoiceElement(string $propertyName): bool
    {
        return str_starts_with($propertyName, 'value') && strlen($propertyName) > 5;
    }

    protected function getPropertyType(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();
        if ($type instanceof \ReflectionNamedType) {
            return $type->getName();
        }

        return null;
    }

    protected function getFirstNonBuiltinTypeFromProperty(\ReflectionProperty $property): ?string
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
            return $type->getName();
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $member) {
                if ($member instanceof \ReflectionNamedType && !$member->isBuiltin() && $member->getName() !== 'null') {
                    return $member->getName();
                }
            }
        }

        return null;
    }

    /**
     * @param \ReflectionClass<object>        $class
     * @param array<string, PropertyMetadata> $metaMap
     */
    protected function resolvePrimitiveArrayItemClass(\ReflectionClass $class, string $fhirType, array $metaMap): ?string
    {
        foreach ($metaMap as $siblingName => $siblingMeta) {
            if ($siblingMeta->propertyKind !== 'primitive' || $siblingMeta->isArray || $siblingMeta->fhirType !== $fhirType) {
                continue;
            }

            if (!$class->hasProperty($siblingName)) {
                continue;
            }

            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($class->getProperty($siblingName));
            if ($primitiveClass !== null) {
                return $primitiveClass;
            }
        }

        return null;
    }

    /**
     * Instantiate an object of the given class, providing constructor default values if a constructor exists.
     *
     * @param \ReflectionClass<object> $reflection
     */
    protected function instantiateWithConstructorDefaults(\ReflectionClass $reflection): object
    {
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            $args = [];
            foreach ($constructor->getParameters() as $param) {
                $args[] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            }

            return $reflection->newInstanceArgs($args);
        }

        return $reflection->newInstanceWithoutConstructor();
    }

    /**
     * For simple typed extensions, copy the first initialised `value*` property back to the inherited `value` slot.
     *
     * @param \ReflectionClass<object> $reflection
     */
    protected function copyTypedExtensionValueBack(\ReflectionClass $reflection, object $object): void
    {
        if (!$reflection->hasProperty('value')) {
            return;
        }

        $valueProperty = $reflection->getProperty('value');
        foreach (self::reflPublicProps($reflection->getName()) as $prop) {
            $propName = $prop->getName();
            if ($propName !== 'value'
                && str_starts_with($propName, 'value')
                && $prop->isInitialized($object)
                && $prop->getValue($object) !== null
            ) {
                $valueProperty->setValue($object, $prop->getValue($object));
                break;
            }
        }
    }

    protected function shouldOmitValue(mixed $value, FHIRSerializationContext $context): bool
    {
        if ($value === null && $context->omitNullValues) {
            return true;
        }

        if (is_array($value) && empty($value) && $context->omitEmptyArrays) {
            return true;
        }

        if (is_string($value) && $value === '') {
            return true;
        }

        return false;
    }

    protected function castNumericScalarForJson(mixed $value, ?PropertyMetadata $meta): mixed
    {
        if ($meta === null || $meta->propertyKind !== 'scalar' || !is_string($value) || !is_numeric($value)) {
            return $value;
        }

        return match ($meta->fhirType) {
            'decimal', 'http://hl7.org/fhirpath/System.Decimal' => (float) $value,
            'integer', 'http://hl7.org/fhirpath/System.Integer',
            'unsignedInt', 'positiveInt'                        => (int) $value,
            default                                             => $value,
        };
    }

    protected function isBuiltinType(string $type): bool
    {
        return in_array($type, ['array', 'string', 'int', 'bool', 'float', 'null', 'mixed', 'object', 'callable', 'iterable'], true);
    }

    protected function isDecimalPrimitive(object $obj): bool
    {
        return $this->findFHIRPrimitiveAttribute(get_class($obj))?->primitiveType === 'decimal';
    }
}
