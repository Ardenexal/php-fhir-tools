<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Registry mapping IG extension URLs, profile URLs, and slice discriminators to their
 * typed PHP classes.
 *
 * Populated at container compile time by FHIRIGRegistryCompilerPass when an IG output
 * directory is configured. Used by the normalizers and type resolver to resolve typed
 * IG classes during deserialization instead of falling back to the base Extension or
 * resource class.
 *
 * Slice discriminators enable automatic resolution of profiled complex type instances
 * (e.g. AUIHIProfile) when deserializing a list typed as the base type (e.g. Identifier[]).
 * The discriminators are keyed by base type FQCN and are matched in registration order.
 *
 * @author Ardenexal
 */
class FHIRIGTypeRegistry
{
    /**
     * @param array<string, array<string, class-string>>                                                      $extensionMappings          Extension URL → [fhirVersion → typed extension class].
     *                                                                                                                                    Inner key is the FHIR version string ('R4', 'R4B', 'R5').
     * @param array<string, class-string>                                                                     $profileMappings            Profile URL → typed profile class
     * @param array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceDiscriminatorMappings
     *                                                                                                                                    Base type FQCN → list of discriminator data arrays. Plain arrays are used here
     *                                                                                                                                    because the Symfony container cannot serialize object instances when dumping the
     *                                                                                                                                    compiled container to PHP/XML. Hydration to SliceDiscriminator objects happens below.
     */
    public function __construct(
        private readonly array $extensionMappings = [],
        private readonly array $profileMappings = [],
        array $sliceDiscriminatorMappings = [],
    ) {
        // Hydrate plain arrays (from the compiled container) to SliceDiscriminator objects.
        $hydrated = [];

        foreach ($sliceDiscriminatorMappings as $baseTypeFqcn => $discriminators) {
            foreach ($discriminators as $d) {
                $hydrated[$baseTypeFqcn][] = new SliceDiscriminator(
                    type: $d['type'],
                    path: $d['path'],
                    value: $d['value'],
                    targetClass: $d['targetClass'],
                );
            }
        }

        $this->sliceDiscriminatorMappings = $hydrated;
    }

    /** @var array<string, list<SliceDiscriminator>> */
    private readonly array $sliceDiscriminatorMappings;

    /**
     * Resolve the typed extension class for the given extension URL, preferring the given FHIR version.
     *
     * When $version is provided and a class is registered for that version, it is returned.
     * Otherwise falls back to any registered version (first-registered wins).
     * Returns null when no typed class is registered for the URL.
     *
     * @return class-string|null
     */
    public function resolveExtensionClass(string $url, string $version = ''): ?string
    {
        $byVersion = $this->extensionMappings[$url] ?? null;

        if ($byVersion === null) {
            return null;
        }

        if ($version !== '' && isset($byVersion[$version])) {
            return $byVersion[$version];
        }

        return reset($byVersion) ?: null;
    }

    /**
     * Resolve the typed profile class for the given profile URL.
     *
     * Returns null when no typed class is registered for the URL,
     * in which case callers should fall back to the base resource class.
     *
     * @return class-string|null
     */
    public function resolveProfileClass(string $profileUrl): ?string
    {
        return $this->profileMappings[$profileUrl] ?? null;
    }

    /**
     * Resolve the typed profile class for a raw FHIR data array using slice discriminators.
     *
     * Iterates all discriminators registered for the given base type FQCN and returns the
     * target class of the first discriminator whose criteria the data satisfies.
     *
     * Discriminator types:
     *   - 'value'   — the value at $discriminator->path in $data must equal $discriminator->value
     *   - 'pattern' — the value at $discriminator->path in $data must contain all keys/values
     *                 from $discriminator->value (recursive subset match)
     *
     * Returns null when no discriminator matches, in which case callers should use the base type.
     *
     * @param class-string         $baseTypeFqcn The FQCN of the base type (e.g. Identifier FQCN)
     * @param array<string, mixed> $data         Raw FHIR data array being deserialized
     *
     * @return class-string|null
     */
    public function resolveSliceClass(string $baseTypeFqcn, array $data): ?string
    {
        $discriminators = $this->sliceDiscriminatorMappings[$baseTypeFqcn] ?? [];

        // Group discriminators by targetClass: all discriminators for a class must match
        // (composite discriminator — AND semantics)
        $byTarget = [];

        foreach ($discriminators as $discriminator) {
            $byTarget[$discriminator->targetClass][] = $discriminator;
        }

        foreach ($byTarget as $targetClass => $targetDiscriminators) {
            $allMatch = true;

            foreach ($targetDiscriminators as $discriminator) {
                $dataAtPath = $this->extractAtPath($data, $discriminator->path);

                $matches = match ($discriminator->type) {
                    'value'   => $this->matchesValue($dataAtPath, $discriminator->value),
                    'pattern' => $this->matchesPattern($dataAtPath, $discriminator->value),
                    default   => false,
                };

                if (!$matches) {
                    $allMatch = false;
                    break;
                }
            }

            if ($allMatch) {
                /** @var class-string $targetClass */
                return $targetClass;
            }
        }

        return null;
    }

    /**
     * Return all registered slice discriminators for a base type FQCN.
     *
     * @param class-string $baseTypeFqcn
     *
     * @return list<SliceDiscriminator>
     */
    public function getSliceDiscriminators(string $baseTypeFqcn): array
    {
        return $this->sliceDiscriminatorMappings[$baseTypeFqcn] ?? [];
    }

    /**
     * @return array<string, array<string, class-string>>
     */
    public function getExtensionMappings(): array
    {
        return $this->extensionMappings;
    }

    /**
     * @return array<string, class-string>
     */
    public function getProfileMappings(): array
    {
        return $this->profileMappings;
    }

    /**
     * @return array<string, list<SliceDiscriminator>>
     */
    public function getSliceDiscriminatorMappings(): array
    {
        return $this->sliceDiscriminatorMappings;
    }

    /**
     * Extract a value from a data array at a dot-notation path.
     *
     * For single-segment paths (most common for discriminators), returns $data[$path] directly.
     * For dot-separated paths (e.g. 'type.coding'), traverses nested arrays.
     *
     * @param array<string, mixed> $data
     */
    private function extractAtPath(array $data, string $path): mixed
    {
        if (!str_contains($path, '.')) {
            return $data[$path] ?? null;
        }

        $segments = explode('.', $path);
        $current  = $data;

        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }

            $current = $current[$segment];
        }

        return $current;
    }

    /**
     * Check whether a data value exactly equals the discriminator value.
     *
     * For string values (e.g. system URI), performs a direct string equality check.
     * For primitive wrapper objects that carry a $value property, unwraps before comparing.
     */
    private function matchesValue(mixed $dataValue, mixed $discriminatorValue): bool
    {
        // Unwrap primitive wrapper objects (UriPrimitive, StringPrimitive, etc.)
        if (is_object($dataValue) && property_exists($dataValue, 'value')) {
            $dataValue = $dataValue->value;
        }

        return $dataValue === $discriminatorValue;
    }

    /**
     * Check whether a data value contains all the keys/values in the discriminator pattern.
     *
     * For CodeableConcept patterns: checks that the data's coding array contains at least
     * one coding that matches all the discriminator coding's fields.
     *
     * For other array patterns: performs a recursive subset check.
     *
     * @param array<string, mixed>|mixed $dataValue
     * @param array<string, mixed>|mixed $patternValue
     */
    private function matchesPattern(mixed $dataValue, mixed $patternValue): bool
    {
        if (!is_array($patternValue)) {
            return $this->matchesValue($dataValue, $patternValue);
        }

        if (!is_array($dataValue)) {
            // If the data is an object, try to normalise it to an array for comparison
            if (is_object($dataValue)) {
                $dataValue = $this->objectToArray($dataValue);
            } else {
                return false;
            }
        }

        // Special case: CodeableConcept-style pattern with 'coding' array.
        // The data must contain at least one coding that matches the pattern coding.
        if (isset($patternValue['coding']) && is_array($patternValue['coding']) && isset($dataValue['coding']) && is_array($dataValue['coding'])) {
            foreach ($patternValue['coding'] as $patternCoding) {
                if (!is_array($patternCoding)) {
                    continue;
                }

                $found = false;

                foreach ($dataValue['coding'] as $dataCoding) {
                    if (!is_array($dataCoding) && is_object($dataCoding)) {
                        $dataCoding = $this->objectToArray($dataCoding);
                    }

                    if (is_array($dataCoding) && $this->arraySubsetMatch($patternCoding, $dataCoding)) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return false;
                }
            }

            return true;
        }

        return $this->arraySubsetMatch($patternValue, $dataValue);
    }

    /**
     * Check whether all key-value pairs in $subset exist in $superset with equal values.
     *
     * @param array<string, mixed> $subset
     * @param array<string, mixed> $superset
     */
    private function arraySubsetMatch(array $subset, array $superset): bool
    {
        foreach ($subset as $key => $expectedValue) {
            if (!array_key_exists($key, $superset)) {
                return false;
            }

            $actualValue = $superset[$key];

            // Unwrap primitive wrapper objects
            if (is_object($actualValue) && property_exists($actualValue, 'value')) {
                $actualValue = $actualValue->value;
            }

            if ($actualValue !== $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert a FHIR model object to an array using its public properties.
     *
     * Used for pattern matching when the deserialized data contains typed objects rather
     * than raw arrays (e.g. a CodeableConcept object instead of an array).
     *
     * @return array<string, mixed>
     */
    private function objectToArray(object $object): array
    {
        $result = [];

        foreach ((new \ReflectionClass($object))->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $result[$prop->getName()] = $prop->getValue($object);
        }

        return $result;
    }
}
