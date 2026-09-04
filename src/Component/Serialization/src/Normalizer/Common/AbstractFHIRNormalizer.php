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
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRConformanceViolationException;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Metadata\UnknownInput;
use Ardenexal\FHIRTools\Component\Metadata\UnknownInputRecorder;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyVariantMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProviderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIROperationMetadataProvider;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIROperationMetadataProviderInterface;

/**
 * Abstract base class for FHIR normalizers providing shared utility methods.
 *
 * @author Ardenexal
 */
abstract class AbstractFHIRNormalizer implements FHIRNormalizerInterface, SerializerAwareInterface
{
    /**
     * Reads generated model shape so this hierarchy never holds a reflection handle itself.
     *
     * Shared and lazily built rather than injected: every subclass in this hierarchy has its own
     * constructor and not all of them chain to a parent, so a constructor-assigned property would be
     * uninitialised on some paths. The accessor is stateless apart from its caches.
     */
    private static ?FHIRModelAccessorInterface $sharedModelAccessor = null;

    /** Shared for the same reason as the accessor above: derived from class shape, so stateless. */
    private static ?FHIRStructureKindProviderInterface $sharedStructureKinds = null;

    /** Shared operation-metadata reader; stateless apart from its caches. */
    private static ?FHIROperationMetadataProviderInterface $sharedOperationMetadata = null;

    /**
     * The shared operation-metadata provider.
     *
     * @return FHIROperationMetadataProviderInterface Reader for operation attributes
     */
    protected static function operationMetadata(): FHIROperationMetadataProviderInterface
    {
        return self::$sharedOperationMetadata ??= new FHIROperationMetadataProvider();
    }

    /**
     * The shared model accessor.
     *
     * @return FHIRModelAccessorInterface Reader for generated model shape
     */
    protected static function modelAccessor(): FHIRModelAccessorInterface
    {
        return self::$sharedModelAccessor ??= new FHIRModelAccessor();
    }

    /**
     * The shared structure-kind provider.
     *
     * The concrete normalizers take one by constructor injection, but methods on this base class are
     * reached from paths that never see it, so they share this one instead. Its caches are derived
     * from generated class shape, so two instances cannot disagree.
     *
     * @return FHIRStructureKindProviderInterface Reader for structural attributes
     */
    protected static function structureKinds(): FHIRStructureKindProviderInterface
    {
        return self::$sharedStructureKinds ??= new FHIRStructureKindProvider();
    }

    protected ?NormalizerInterface $normalizer = null;

    protected ?DenormalizerInterface $denormalizer = null;

    /** @var array<string, FHIRPrimitive|null> */
    private array $primitiveAttributeCache = [];

    /** @var array<class-string, array<string, array{0: string, 1: string, 2: string}>> */
    private static array $choiceKeyIndexCache = [];

    /** @var array<class-string, array<string, mixed>> */
    private static array $ctorDefaultsCache = [];

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

        // An unloadable name answers null here rather than throwing, which is what the caught
        // ReflectionException produced when this walked the chain itself.
        return $this->primitiveAttributeCache[$type] = self::structureKinds()->nearestPrimitiveAttribute($type);
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

        $result = [];
        $raw    = self::modelAccessor()->readInitializedValue($value, 'value');

        // Each conversion below tests its own input, so running them on a null read is a no-op and
        // the guarded read needs no initialisation branch of its own.
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

        if ($includeExtensions) {
            // A class with no `extension` property and one whose slot is unwritten both read as null,
            // which is the same skip the absent-property branch used to make separately.
            $extensions = self::modelAccessor()->readInitializedValue($value, 'extension');

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
     * Serialize an array of primitives into parallel FHIR JSON arrays.
     *
     * Returns ['values' => [...], 'extensions' => [...]] where 'extensions' is only present
     * when at least one item carries an extension.
     *
     * @param array<mixed>         $array
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|null
     */
    protected function normalizeArrayWithExtensions(array $array, string $propertyName, FHIRSerializationContext $fhirContext, array $context): ?array
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
                        $extensions[$index] = ['extension' => $primitiveResult['extensions']];
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
            $result   = [];
            $accessor = self::modelAccessor();

            foreach ($accessor->publicPropertyNames($value) as $propertyName) {
                $propertyValue = $accessor->readInitializedValue($value, $propertyName);
                if ($propertyValue !== null) {
                    $result[$propertyName] = $this->normalizeBasicValue($propertyValue, $format, $context);
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
     * @param object|string                   $owner        An instance or class name owning the property
     * @param string                          $propertyName Property being filled
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function denormalizePrimitiveProperty(
        PropertyMetadata $meta,
        object|string $owner,
        string $propertyName,
        mixed $value,
        ?string $format,
        array $context,
        array $metaMap,
    ): mixed {
        if ($this->denormalizer === null) {
            return $value;
        }

        if ($meta->isArray && is_array($value)) {
            $primitiveClass = $meta->phpItemClass ?? $this->resolvePrimitiveArrayItemClass($owner, $meta->fhirType, $metaMap);
            if ($primitiveClass === null) {
                return $value;
            }

            // One occurrence of a repeating element is not a list. `XmlEncoder` decodes
            // `<profile value="X"/>` to the element's own map, `['@value' => 'X', '#' => '']`, and
            // only decodes two or more occurrences to a list of such maps. Iterating the
            // single-occurrence map walks its *keys*, so `Meta.profile` came back holding two
            // primitives from one element: 'X' from `@value`, then '' from `#`. Two occurrences in
            // yielded two out, which is why this read as correct for so long.
            //
            // Wrapping is what every sibling branch in FHIRComplexTypeXmlNormalizer already does
            // (search: `!array_is_list`); this one was the omission. It also restores the child
            // extension of a lone occurrence, which the key walk dropped along with the count.
            //
            // JSON is unaffected: a repeating primitive is always a JSON array, so it is always a
            // list here and takes the same path it did before.
            $items = array_is_list($value) ? $value : [$value];

            $result = [];
            foreach ($items as $item) {
                $result[] = $this->denormalizer->denormalize($item, $primitiveClass, $format, $context);
            }

            return $result;
        }

        if (!$meta->isArray) {
            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($owner, $propertyName);
            if ($primitiveClass === null) {
                // XML encodes primitive values as attributes: <lang value="x"/> → ['@value' => 'x'].
                // Builtin PHP types can't hold extensions, so extract the scalar or return null.
                if (is_array($value) && isset($value['@value'])) {
                    return $value['@value'];
                }

                return is_array($value) ? null : $value;
            }

            return $this->denormalizer->denormalize($value, $primitiveClass, $format, $context);
        }

        return $value;
    }

    /**
     * Second-pass: attach underscore-prefixed extension data to already-denormalized primitive properties.
     *
     * @param array<string, mixed>            $data
     * @param array<string, PropertyMetadata> $metaMap
     * @param array<string, mixed>            $context
     */
    protected function applyPrimitiveExtensions(
        object $object,
        array $data,
        array $metaMap,
        ?string $format,
        array $context,
    ): void {
        $accessor = self::modelAccessor();

        foreach ($data as $elementName => $extData) {
            if (!str_starts_with($elementName, '_')) {
                continue;
            }

            $baseName = substr($elementName, 1);
            $meta     = $metaMap[$baseName] ?? null;

            if ($meta === null || $meta->propertyKind !== 'primitive' || !$accessor->hasProperty($object, $baseName)) {
                continue;
            }

            if (!$meta->isArray) {
                if (!is_array($extData) || !isset($extData['extension']) || !is_array($extData['extension'])) {
                    continue;
                }

                $current = $accessor->readInitializedValue($object, $baseName);

                if (!is_object($current)) {
                    $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($object, $baseName);
                    if ($primitiveClass === null || $this->denormalizer === null) {
                        continue;
                    }

                    $rawValue = is_scalar($current) ? $current : null;
                    $current  = $this->denormalizer->denormalize($rawValue, $primitiveClass, $format, $context);
                    $accessor->writeValue($object, $baseName, $current);
                }

                // A class with no `extension` property is skipped by writeValue itself, which is what
                // the absent-handle check did.
                $accessor->writeValue(
                    $current,
                    'extension',
                    $this->denormalizeExtensionArray($extData['extension'], $format, $context),
                );
            } else {
                if (!is_array($extData)) {
                    continue;
                }

                // Not a plain guarded read: an unwritten slot must continue as an empty array while
                // an initialised null must fall through to the is_array skip below, and one null
                // answer cannot express both.
                $currentArray = $accessor->isPropertyInitialized($object, $baseName)
                    ? $accessor->readInitializedValue($object, $baseName)
                    : [];

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

                        $primitiveClass = $meta->phpItemClass ?? $this->resolvePrimitiveArrayItemClass($object, $meta->fhirType, $metaMap);
                        if ($primitiveClass === null || $this->denormalizer === null) {
                            continue;
                        }

                        $currentArray[$i] = $this->denormalizer->denormalize(null, $primitiveClass, $format, $context);
                    }

                    if (!$accessor->hasProperty($currentArray[$i], 'extension')) {
                        continue;
                    }

                    if ($hasExtensionData) {
                        $accessor->writeValue(
                            $currentArray[$i],
                            'extension',
                            $this->denormalizeExtensionArray($extEntry['extension'], $format, $context),
                        );
                    } elseif (!$accessor->isPropertyInitialized($currentArray[$i], 'extension')) {
                        // Only an unwritten slot gets the default; an explicit null is left alone.
                        $accessor->writeValue($currentArray[$i], 'extension', []);
                    }
                }

                $accessor->writeValue($object, $baseName, $currentArray);
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
     *
     * The record is written whatever the policy says, because recording is not a behaviour: it
     * neither places the value nor rejects the document, so every existing policy reads as it did
     * before. Only validation decides whether a record becomes a finding.
     *
     * @param string $propertyName the element or property name as the document spelled it
     * @param mixed  $value        the value that could not be placed
     * @param string $policy       one of FHIRSerializationContext::UNKNOWN_POLICY_*
     * @param string $format       UnknownInput::FORMAT_JSON or FORMAT_XML, which decides the wording
     * @param object $object       the model object being read into, and the record's key
     * @param string $elementPath  path for the error message, when the policy throws
     */
    protected function handleUnknownProperty(string $propertyName, mixed $value, string $policy, string $format, object $object, ?string $elementPath = null): void
    {
        UnknownInputRecorder::record($object, new UnknownInput($propertyName, $format));

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
            // Constructor-defaulted, not bare: a primitive wrapper carries inherited `id` and
            // `extension` slots that this method never assigns when the payload omits them, and an
            // unassigned slot throws on read rather than reading back as the declared default.
            //
            // Built before the value is validated so an unloadable type still raises
            // ReflectionException first, as reflecting the class up front used to.
            $instance = $this->instantiateWithDefaults($type);
            $accessor = self::modelAccessor();

            $accessor->writeValue($instance, 'value', $this->validateAndConvertValue($value, $type));

            if ($extensions !== null) {
                $accessor->writeValue(
                    $instance,
                    'extension',
                    $this->denormalizer !== null && is_array($extensions)
                        ? $this->denormalizeExtensionArray($extensions, $format, $context)
                        : $extensions,
                );
            }

            return $instance;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Coerce a raw primitive value to the PHP representation its FHIR primitive type requires.
     *
     * Reads the FHIRPrimitive attribute on $type and dispatches to the matching validator/parser
     * (string, integer, decimal, boolean, or temporal). Returns the value unchanged when $type
     * carries no FHIRPrimitive attribute or its primitiveType is unrecognised.
     */
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
     * Read a temporal lexeme, retaining it rather than aborting the document when it will not parse.
     *
     * Malformed primitive syntax is a FHIR *validation* finding, not a reason to refuse the
     * document: the reference validator reads `primitive-bad.xml` end to end and reports forty
     * located errors, one per bad primitive. Throwing here surfaced only the first bad temporal and
     * lost the other thirty-nine — the document never reached the validator at all.
     *
     * The lexeme is therefore kept on the value object ({@see FHIRTemporalValue::unparsed()}), where
     * validation locates it and reports it, and serialization writes it back exactly as supplied.
     *
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
                return $class::unparsed($value, $e->getMessage());
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
    /**
     * Reject a repeated XML element landing on a single-valued property.
     *
     * XML has no array syntax: repetition is expressed by repeating the element, so
     * `<subject/><subject/>` decodes to a *list* while one `<subject/>` decodes to a map. A property
     * the model declares as `0..1` therefore receives a list only when the document exceeds its
     * maximum cardinality — `Composition.subject` is `0..1`, and `bundle-dual-subject.xml` supplies
     * two.
     *
     * Without this guard the list reached the complex-type normalizer, whose element loop assumes
     * string keys, and `str_starts_with(0, '@')` raised a `TypeError`. That surfaced as an opaque
     * "Format error (xml)" naming neither the element nor the real problem — and because a failed
     * deserialization is seeded as one error, it coincidentally matched the Java reference validator's
     * single cardinality error and looked correct.
     *
     * The model cannot hold both values, so this is fatal for the document rather than something
     * validation can report later. The message says which element and how many, mirroring Java's
     * "max allowed = 1, but found 2".
     *
     * @throws FHIRSerializationException when $value repeats and the property cannot hold a list
     */
    protected static function assertSingleValuedElement(string $elementName, mixed $value, string $ownerType): void
    {
        if (!is_array($value) || !array_is_list($value) || count($value) < 2) {
            return;
        }

        throw FHIRConformanceViolationException::inFormat('xml', sprintf('%s.%s: max allowed = 1, but found %d', self::shortTypeName($ownerType), $elementName, count($value)));
    }

    /**
     * Reject a repeating element supplied as a JSON object instead of an array.
     *
     * FHIR JSON represents a `0..*` element as an array, always — even for a single occurrence. An
     * object there is malformed, and the HL7 Java reference validator reports it as an error ("The
     * property reasonCode must be a JSON Array, not an Object"), so accepting it leniently would make
     * us pass documents the oracle rejects.
     *
     * The alternative is worse than a wrong answer: iterating the object walks its *fields* into the
     * item normalizer, where an integer key reaches `str_starts_with()` and raises a `TypeError`
     * reported only as an opaque "Format error (json)".
     *
     * @throws FHIRSerializationException when $value is an object rather than a list
     */
    protected static function assertRepeatingElementIsArray(string $elementName, mixed $value, string $ownerType): void
    {
        if (!is_array($value) || array_is_list($value)) {
            return;
        }

        throw FHIRConformanceViolationException::inFormat('json', sprintf('The property %s must be a JSON Array, not an Object (at %s)', $elementName, self::shortTypeName($ownerType)));
    }

    /**
     * Was this XML element present in the document but carrying nothing an element can hold?
     *
     * Symfony's XmlEncoder decodes `<entry/>` and `<code>\n</code>` to the empty or whitespace-only
     * string, and `<entry><!-- c --></entry>` to the empty array — three spellings of the same fact.
     *
     * That is a *validation* finding, not a reason to refuse the document: the reference validator
     * reads `list-empty1.xml` end to end and reports ele-1 ("Element must have some content") at the
     * offending path. Refusing it lost the whole file. Callers therefore substitute `[]` — an element
     * that is present with no children — and let `ele-1` on {@see Element} report it.
     */
    protected static function isEmptyXmlElement(mixed $value): bool
    {
        return $value === [] || (is_string($value) && trim($value) === '');
    }

    /**
     * Reject a complex-valued JSON property supplied as `null`.
     *
     * FHIR JSON has no null for a complex element: an element is either present as an object or
     * absent. The HL7 Java reference validator reports the null as an error ("The property meta must
     * be an Object, not a Null (at Bundle.meta)") rather than reading it, so accepting it leniently
     * would make us pass documents the oracle rejects.
     *
     * This is deliberately *not* applied on the primitive path: FHIR legitimately uses `null`
     * placeholders inside a primitive array to align it with its `_x` extension array.
     *
     * @throws FHIRSerializationException when $value is null
     */
    protected static function assertComplexPropertyIsNotNull(string $elementName, mixed $value, string $ownerType): void
    {
        if ($value !== null) {
            return;
        }

        throw FHIRConformanceViolationException::inFormat('json', sprintf('The property %s must be an Object, not a Null (at %s.%s)', $elementName, self::shortTypeName($ownerType), $elementName));
    }

    /**
     * The FHIR type name for a generated class, for messages a reader can match against the spec.
     *
     * Reads the class's own structural attribute, so the message says `Composition.subject` rather
     * than `CompositionResource.subject`, `Dosage.doseAndRate.type` rather than
     * `DosageDoseAndRate.type`, and `Group.member` rather than `ActualGroupProfile.member` -- lining
     * up with the reference validator's wording, which is what the conformance corpus compares
     * against.
     *
     * Do not be tempted to drop this and use the short class name: it is right only for the ordinary
     * complex types (`Coding` really is `Coding`), which is precisely why a version of this that
     * always fell through to the class name went unnoticed.
     */
    private static function shortTypeName(string $fqcn): string
    {
        $declared = self::structureKinds()->declaredFhirTypeName($fqcn);

        if ($declared !== null) {
            return $declared;
        }

        // No declared name: an unloadable string, or a subclass carrying no structural attribute of
        // its own. Both fall back to the class's short name.
        $tail = strrchr($fqcn, '\\');

        return $tail === false ? $fqcn : substr($tail, 1);
    }

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

    protected function getPropertyType(object|string $subject, string $propertyName): ?string
    {
        return self::modelAccessor()->declaredTypeOf($subject, $propertyName);
    }

    /**
     * Resolve the first class/interface type a property declares, ignoring builtins and null.
     *
     * For a named type, returns its name when it is not a builtin. For a union type (e.g.
     * FHIRString|null), returns the first non-builtin, non-null member. Returns null when the
     * property has no usable class type — typically a primitive whose value cannot hold extensions.
     */
    protected function getFirstNonBuiltinTypeFromProperty(object|string $subject, string $propertyName): ?string
    {
        return self::modelAccessor()->declaredClassOf($subject, $propertyName);
    }

    /**
     * @param object|string                   $subject An instance or class name owning the sibling properties
     * @param array<string, PropertyMetadata> $metaMap
     */
    protected function resolvePrimitiveArrayItemClass(object|string $subject, string $fhirType, array $metaMap): ?string
    {
        foreach ($metaMap as $siblingName => $siblingMeta) {
            if ($siblingMeta->propertyKind !== 'primitive' || $siblingMeta->isArray || $siblingMeta->fhirType !== $fhirType) {
                continue;
            }

            if (!self::modelAccessor()->hasProperty($subject, $siblingName)) {
                continue;
            }

            $primitiveClass = $this->getFirstNonBuiltinTypeFromProperty($subject, $siblingName);
            if ($primitiveClass !== null) {
                return $primitiveClass;
            }
        }

        return null;
    }

    /**
     * Render a JSON-decoded number as the FHIR `decimal` lexical form it was written in.
     *
     * Generated models store `decimal` as a string precisely to keep the author's precision, but
     * `json_decode()` has already turned `101.0` into PHP float `101.0`, and `(string)` on that float
     * loses two things:
     *
     *  - the decimal point — `(string) 101.0` is `"101"`, which reads as an integer. FHIRPath type
     *    detection for choice elements sniffs for a `.` to tell `decimal` from `integer`
     *    (`FHIRPathEvaluator::resolveChoiceVariantType()`), so `probability is decimal` came out false
     *    and `ras-2` (`probability is decimal implies (probability as decimal) <= 100`) short-circuited
     *    to a silent pass on a probability of 101;
     *  - significant digits — the cast honours `precision` (14), so `1.23456789012345678` became
     *    `"1.2345678901235"`.
     *
     * `JSON_PRESERVE_ZERO_FRACTION` keeps the `.0` that both the cast and a plain `json_encode()` drop,
     * and `serialize_precision = -1` makes it the shortest representation that round-trips exactly.
     * Integers are left as written: JSON `101` for a decimal element is lexically `"101"`, and inventing
     * a fraction there would change serialized output.
     */
    protected static function decimalToLexicalString(mixed $value): string
    {
        if (is_float($value)) {
            $encoded = json_encode($value, JSON_PRESERVE_ZERO_FRACTION);
            if (is_string($encoded)) {
                return $encoded;
            }
        }

        return is_scalar($value) ? (string) $value : '';
    }

    /**
     * Instantiate a class the way `newInstanceWithoutConstructor()` cannot: in the state PHP's own
     * constructor would have produced.
     *
     * The denormalizers bypass the constructor, so every property the constructor would have
     * defaulted is left *uninitialized* instead — a third state the model never declared, and the
     * only one that throws on read:
     *
     *  - reading `$resource->item` or `$patient->gender` raises "must not be accessed before
     *    initialization" for consumers, and `?->` does not help (null-safe guards against null,
     *    which uninitialized is not);
     *  - Symfony's `PropertyMetadata::getPropertyValue()` maps the uninitialized slot to `null`, and
     *    `CountValidator` returns early on null, so every generated `#[Count(min: 1)]` was dead.
     *
     * The guarantee made here is a single one — *a denormalized instance is in the same state the
     * constructor would have produced* — rather than a list of per-type special cases: every
     * uninitialized public property whose constructor parameter declares a default gets that
     * default. Generated models declare repeating elements as `public array $x = []` and optional
     * elements as `public ?T $x = null`, so both come out right from the same rule. Matching is by
     * parameter *name*, resolved across the whole class hierarchy rather than the most-derived
     * constructor alone; see `constructorDefaults()` for which parameters a child constructor hides.
     *
     * Where no constructor default exists the property is left uninitialized — that is genuinely the
     * model's choice and not an artefact. The one exception is a non-nullable `array`, which is
     * still filled with `[]`: FHIR has no representation for an absent repeating element, so `[]` is
     * the only state it can be in.
     *
     * @param object|string $subject An instance or class name to build from
     */
    protected function instantiateWithDefaults(object|string $subject): object
    {
        return self::modelAccessor()->instantiateWithDefaults($subject);
    }

    /**
     * Constructor parameter name → default value, for every parameter that declares one anywhere in
     * the class hierarchy.
     *
     * The whole chain is walked because generated subclasses narrow their signature and forward the
     * rest to `parent::__construct()`, and which parameters that hides depends on the kind. A
     * code-type wrapper declares only `__construct(?string $value = null)`, so its ancestor's `id`
     * and `extension` are the invisible ones. A typed extension declares `id` and its own
     * `value<Type>`, but supplies `url` as a computed argument, and does the same for the inherited
     * `value` whenever it narrows to a typed one. Ancestors are merged first so the most-derived
     * declaration wins on any name they share.
     *
     * Cached per concrete class: defaults are constant expressions (null, scalars, arrays, enum
     * cases), all of which are immutable or copied on assignment, so one resolved value is safely
     * shared across instances.
     *
     * @param object|string $subject An instance or class name to read constructor defaults from
     *
     * @return array<string, mixed>
     */
    private static function constructorDefaults(object|string $subject): array
    {
        return self::modelAccessor()->constructorDefaults($subject);
    }

    /**
     * Instantiate an object of the given class, providing constructor default values if a constructor exists.
     *
     * @param object|string $subject An instance or class name to build from
     */
    protected function instantiateWithConstructorDefaults(object|string $subject): object
    {
        return self::modelAccessor()->instantiateWithConstructorDefaults($subject);
    }

    /**
     * For simple typed extensions, copy the first initialised `value*` property back to the inherited `value` slot.
     *
     * @param object $object The extension instance to normalize in place
     */
    protected function copyTypedExtensionValueBack(object $object): void
    {
        self::modelAccessor()->copyTypedExtensionValueBack($object);
    }

    /**
     * Decide whether a value should be dropped from serialized output.
     *
     * Omits nulls and empty arrays only when the context opts in (omitNullValues / omitEmptyArrays),
     * but always omits empty strings since FHIR has no representation for an empty primitive value.
     */
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

    /**
     * Re-type a numeric-string scalar to the native JSON number its FHIR type requires.
     *
     * FHIR JSON represents decimals as JSON numbers and integers as JSON integers, so a value
     * carried internally as a numeric string is cast to float or int based on the property's
     * FHIR type. Non-scalar, non-numeric, or non-'scalar'-kind values are returned untouched.
     */
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
