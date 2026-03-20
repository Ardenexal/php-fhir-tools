<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizer for FHIR primitive types with extension support.
 *
 * This normalizer handles serialization and deserialization of FHIR primitive types
 * following the official FHIR JSON and XML specifications. It implements underscore
 * notation for primitive extensions in JSON, handles primitive value validation and
 * type conversion, adds XML attribute serialization support for primitives, and
 * supports primitive extension round-trip preservation.
 *
 * @author Ardenexal
 */
class FHIRPrimitiveTypeNormalizer extends AbstractFHIRNormalizer
{
    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
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

        if (!$this->metadataExtractor->isPrimitiveType($object)) {
            throw new InvalidArgumentException('Object is not a FHIR primitive type');
        }

        $reflection = new \ReflectionClass($object);

        // Handle JSON format with underscore notation for extensions
        if ($format === 'json' || $format === null) {
            return $this->normalizeForJSON($object, $reflection, $context);
        }

        // Handle XML format with attributes and child elements
        if ($format === 'xml') {
            return $this->normalizeForXML($object, $reflection, $context);
        }

        // Default to JSON format
        return $this->normalizeForJSON($object, $reflection, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (!is_object($data)) {
            return false;
        }

        return $this->metadataExtractor->isPrimitiveType($data);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        // Handle different input formats
        if (is_scalar($data) || is_null($data)) {
            // Simple primitive value without extensions
            return $this->createPrimitiveInstance($type, $data, null);
        }

        if (is_array($data)) {
            // Complex primitive with potential extensions
            return $this->denormalizeFromArray($data, $type, $format, $context);
        }

        throw new NotNormalizableValueException(sprintf('Cannot denormalize data of type %s to %s', gettype($data), $type));
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $this->hasFHIRPrimitiveAttribute($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        // This normalizer supports any class with the FHIRPrimitive attribute
        return ['object' => true];
    }

    /**
     * Check whether a class or any of its ancestors carries the FHIRPrimitive attribute.
     *
     * Generated "Type" wrapper classes (e.g. NarrativeStatusType) extend CodePrimitive
     * which carries the attribute, so we must walk the parent chain.
     */
    private function hasFHIRPrimitiveAttribute(string $type): bool
    {
        return $this->findFHIRPrimitiveAttribute($type) !== null;
    }

    /**
     * Walk the class hierarchy and return the first FHIRPrimitive attribute instance found, or null.
     */
    private function findFHIRPrimitiveAttribute(string $type): ?FHIRPrimitive
    {
        try {
            /** @var class-string $type */
            $reflection = new \ReflectionClass($type);

            do {
                $attributes = $reflection->getAttributes(FHIRPrimitive::class);
                if (!empty($attributes)) {
                    /** @var FHIRPrimitive */
                    return $attributes[0]->newInstance();
                }

                $reflection = $reflection->getParentClass();
            } while ($reflection !== false);

            return null;
        } catch (\ReflectionException) {
            return null;
        }
    }

    /**
     * Normalize primitive for JSON format with underscore notation for extensions.
     *
     * @param \ReflectionClass<object> $reflection
     * @param array<string, mixed>     $context
     */
    private function normalizeForJSON(object $object, \ReflectionClass $reflection, array $context): mixed
    {
        $value      = null;
        $extensions = null;

        // Extract value
        if ($reflection->hasProperty('value')) {
            $valueProperty = $reflection->getProperty('value');
            if ($valueProperty->isInitialized($object)) {
                $value = $valueProperty->getValue($object);
            }
        }

        // Format temporal value objects to string for JSON serialization
        if ($value instanceof FHIRTemporalValue) {
            $value = (string) $value;
        }

        // String decimals: convert to float for JSON number output (FHIR JSON spec requires numbers).
        // String representation is only preserved for XML attribute output.
        // IMPORTANT: only convert for DecimalPrimitive — other string-typed primitives (e.g.
        // StringPrimitive, UriPrimitive) may hold numeric-looking values that must remain strings.
        if (is_string($value) && is_numeric($value)) {
            $decimalAttr = $this->findFHIRPrimitiveAttribute(get_class($object));
            if ($decimalAttr !== null && $decimalAttr->primitiveType === 'decimal') {
                $value = (float) $value;
            }
        }

        // Extract extensions
        if ($reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensionValue    = $extensionProperty->isInitialized($object) ? $extensionProperty->getValue($object) : null;

            if ($extensionValue !== null && !empty($extensionValue)) {
                if ($this->normalizer !== null) {
                    $extensions = $this->normalizer->normalize($extensionValue, 'json', $context);
                } else {
                    $extensions = $extensionValue;
                }
            }
        }

        // For JSON, if there are no extensions, return just the value
        if ($extensions === null || empty($extensions)) {
            return $value;
        }

        // If there are extensions but no value, return null (FHIR rule)
        if ($value === null) {
            return null;
        }

        // Return the value - extensions will be handled by the parent normalizer
        // using underscore notation
        return $value;
    }

    /**
     * Normalize primitive for XML format with attributes and child elements.
     *
     * @param \ReflectionClass<object> $reflection
     * @param array<string, mixed>     $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, \ReflectionClass $reflection, array $context): array
    {
        $result = [];

        // Extract value for XML attribute
        if ($reflection->hasProperty('value')) {
            $valueProperty = $reflection->getProperty('value');
            $value         = $valueProperty->isInitialized($object) ? $valueProperty->getValue($object) : null;

            // Format temporal value objects to string for XML serialization
            if ($value instanceof FHIRTemporalValue) {
                $value = (string) $value;
            }

            // XmlEncoder casts PHP booleans to int (true→1, false→0). FHIR XML requires
            // "true"/"false" string literals for boolean attributes.
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if ($value !== null) {
                $result['@value'] = $value;
            }
        }

        // Extract extensions for XML child elements
        if ($reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions        = $extensionProperty->isInitialized($object) ? $extensionProperty->getValue($object) : null;

            if ($extensions !== null && !empty($extensions)) {
                if ($this->normalizer !== null) {
                    $result['extension'] = $this->normalizer->normalize($extensions, 'xml', $context);
                } else {
                    $result['extension'] = $extensions;
                }
            }
        }

        return $result;
    }

    /**
     * Denormalize from array data (complex primitive with extensions).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromArray(array $data, string $type, ?string $format, array $context): mixed
    {
        // Clean XML encoder artifacts (@ prefixes, # text nodes)
        if ($format === 'xml') {
            $data = $this->cleanXmlArtifacts($data);
        }

        $value      = null;
        $extensions = null;

        // Handle JSON format
        if ($format === 'json' || $format === null) {
            // In JSON, the value might be directly in the data or in a 'value' key
            if (isset($data['value'])) {
                $value = $data['value'];
            } elseif (count($data) === 1 && !isset($data['extension'])) {
                // Single value without extensions
                $value = reset($data);
            }

            // Extensions might be in 'extension' key or handled externally
            if (isset($data['extension'])) {
                $extensions = $data['extension'];
            }
        }

        // Handle XML format
        if ($format === 'xml') {
            // After cleanXmlArtifacts(), @value becomes value
            if (isset($data['value'])) {
                $value = $data['value'];
            }

            // Extensions are in extension child elements.
            // XmlEncoder gives a single <extension> as an associative array and multiple
            // <extension> elements as a list array. Normalise to a list so that
            // createPrimitiveInstance() iterates over individual extension items.
            if (isset($data['extension'])) {
                $raw        = $data['extension'];
                $extensions = (is_array($raw) && !array_is_list($raw)) ? [$raw] : $raw;
            }
        }

        return $this->createPrimitiveInstance($type, $value, $extensions, $format, $context);
    }

    /**
     * Create a primitive instance with value and extensions.
     *
     * @param array<string, mixed> $context
     */
    private function createPrimitiveInstance(string $type, mixed $value, mixed $extensions, ?string $format = null, array $context = []): mixed
    {
        try {
            /** @var class-string $type */
            $reflection = new \ReflectionClass($type);

            // Validate and convert the value based on primitive type
            $validatedValue = $this->validateAndConvertValue($value, $type);

            // Create instance
            $instance = $reflection->newInstanceWithoutConstructor();

            // Set value property
            if ($reflection->hasProperty('value')) {
                $valueProperty = $reflection->getProperty('value');
                $valueProperty->setValue($instance, $validatedValue);
            }

            // Set extension property
            if ($extensions !== null && $reflection->hasProperty('extension')) {
                $extensionProperty = $reflection->getProperty('extension');

                // Denormalize extensions to Extension objects
                if ($this->denormalizer !== null && is_array($extensions)) {
                    $denormalizedExtensions = [];
                    foreach ($extensions as $extension) {
                        if (is_array($extension)) {
                            // Denormalize to Extension object (cleanXmlArtifacts already handled @ prefixes)
                            $extensionClass           = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension';
                            $denormalizedExtensions[] = $this->denormalizer->denormalize($extension, $extensionClass, $format, $context);
                        } else {
                            $denormalizedExtensions[] = $extension;
                        }
                    }
                    $extensionProperty->setValue($instance, $denormalizedExtensions);
                } else {
                    $extensionProperty->setValue($instance, $extensions);
                }
            }

            return $instance;
        } catch (\ReflectionException $e) {
            throw new NotNormalizableValueException(sprintf('Cannot create instance of class "%s": %s', $type, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Validate and convert value based on primitive type.
     */
    private function validateAndConvertValue(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        $primitiveAttribute = $this->findFHIRPrimitiveAttribute($type);

        if ($primitiveAttribute === null) {
            return $value; // Not a FHIR primitive, return as-is
        }

        $primitiveType = $primitiveAttribute->primitiveType;

        return match ($primitiveType) {
            'string', 'code', 'uri', 'url', 'canonical', 'base64Binary',
            'oid', 'id', 'uuid',
            'markdown', 'xhtml' => $this->validateString($value),
            'date'     => $this->parseTemporalValue($value, FHIRDate::class),
            'time'     => $this->parseTemporalValue($value, FHIRTime::class),
            'dateTime' => $this->parseTemporalValue($value, FHIRDateTime::class),
            'instant'  => $this->parseTemporalValue($value, FHIRInstant::class),
            'integer', 'positiveInt', 'unsignedInt' => $this->validateInteger($value),
            'decimal' => $this->validateDecimal($value),
            'boolean' => $this->validateBoolean($value),
            default   => $value, // Unknown type, return as-is
        };
    }

    /**
     * Validate string value.
     */
    private function validateString(mixed $value): ?string
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

    /**
     * Validate integer value.
     */
    private function validateInteger(mixed $value): ?int
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
     * Validate decimal value.
     *
     * All inputs are converted to numeric-string to preserve exact FHIR decimal precision
     * (e.g. "1.0" vs "1.00") for XML round-trips. Float inputs are unavoidably lossy
     * (precision already lost by PHP), but string and int inputs are exact.
     *
     * @return numeric-string|null
     */
    private function validateDecimal(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value)) {
            // XmlEncoder type-casts "1.0" → float 1.0. Preserve at least one decimal place
            // so "1.0" round-trips correctly rather than becoming "1".
            $str = rtrim(sprintf('%.14F', $value), '0');
            $str = str_ends_with($str, '.') ? $str . '0' : $str;
            assert(is_numeric($str));

            return $str;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            // Preserve the original string representation for precision-sensitive XML output.
            return $value;
        }

        throw new NotNormalizableValueException(sprintf('Expected decimal value, got %s', gettype($value)));
    }

    /**
     * Validate boolean value.
     */
    private function validateBoolean(mixed $value): ?bool
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
     * Parse a raw scalar input into one of the four FHIR temporal value objects.
     *
     * @param class-string<FHIRTemporalValue> $class
     */
    private function parseTemporalValue(mixed $value, string $class): ?FHIRTemporalValue
    {
        if ($value === null) {
            return null;
        }

        // Already the correct type (e.g. round-tripped through the normalizer)
        if ($value instanceof $class) {
            return $value;
        }

        // XmlEncoder may cast numeric year strings (e.g. "2012") to int when
        // TYPE_CAST_ATTRIBUTES is enabled. Coerce to string before parsing.
        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (is_string($value)) {
            try {
                return $class::parse($value);
            } catch (\Throwable $e) {
                throw new NotNormalizableValueException(
                    sprintf('Expected %s string, got invalid value: %s', $class, $value),
                    0,
                    $e,
                );
            }
        }

        throw new NotNormalizableValueException(
            sprintf('Expected %s value, got %s', $class, gettype($value)),
        );
    }
}
