<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;

/**
 * Resolves and caches PropertyMetadata for FHIR model classes.
 *
 * Resolution order per class:
 *  1. Warm in-process cache (keyed by class-string, populated on first access)
 *  2. FHIR_PROPERTY_MAP const — O(1) array read, no reflection (preferred for generated classes)
 *  3. #[FhirProperty] attribute reflection — once per class per process for non-generated classes
 *  4. Empty array — class has no FHIR property metadata
 *
 * @author Ardenexal
 */
class PropertyMetadataProvider implements PropertyMetadataProviderInterface
{
    /** @var array<class-string, array<string, PropertyMetadata>> */
    private array $cache = [];

    /**
     * {@inheritDoc}
     */
    public function getPropertyMetadata(string $className): array
    {
        if (array_key_exists($className, $this->cache)) {
            return $this->cache[$className];
        }

        $metadata = $this->resolveMetadata($className);
        $this->cache[$className] = $metadata;

        return $metadata;
    }

    /**
     * Resolve metadata for a class — const path first, attribute reflection second.
     *
     * @param class-string $className
     *
     * @return array<string, PropertyMetadata>
     */
    private function resolveMetadata(string $className): array
    {
        // Fast path: read compiled FHIR_PROPERTY_MAP const (no reflection)
        if (defined($className . '::FHIR_PROPERTY_MAP')) {
            /** @var array<string, array{fhirType: string, propertyKind: string, isArray: bool, isRequired: bool, isChoice: bool, jsonKey: null|string, variants: null|list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string, isBuiltin: bool}>}> $map */
            $map = constant($className . '::FHIR_PROPERTY_MAP');

            return $this->hydrateFromMap($map);
        }

        // Slow path: walk constructor promoted parameters for #[FhirProperty] attributes
        return $this->resolveFromAttributes($className);
    }

    /**
     * Hydrate PropertyMetadata objects from a FHIR_PROPERTY_MAP const value.
     *
     * @param array<string, array{fhirType: string, propertyKind: string, isArray: bool, isRequired: bool, isChoice: bool, jsonKey: null|string, variants: null|list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string, isBuiltin: bool}>}> $map
     *
     * @return array<string, PropertyMetadata>
     */
    private function hydrateFromMap(array $map): array
    {
        $result = [];

        foreach ($map as $propertyName => $entry) {
            $variants = null;
            if ($entry['isChoice'] && isset($entry['variants'])) {
                $variants = array_map(
                    static fn (array $v): PropertyVariantMetadata => new PropertyVariantMetadata(
                        $v['fhirType'],
                        $v['propertyKind'],
                        $v['phpType'],
                        $v['jsonKey'],
                        $v['isBuiltin'],
                    ),
                    $entry['variants'],
                );
            }

            $result[$propertyName] = new PropertyMetadata(
                $entry['fhirType'],
                $entry['propertyKind'],
                $entry['isArray'],
                $entry['isRequired'],
                $entry['isChoice'],
                $variants,
                $entry['jsonKey'],
            );
        }

        return $result;
    }

    /**
     * Resolve metadata by reflecting #[FhirProperty] attributes on constructor parameters.
     *
     * Used only for classes that do not have a FHIR_PROPERTY_MAP const (e.g. hand-written models).
     *
     * @param class-string $className
     *
     * @return array<string, PropertyMetadata>
     */
    private function resolveFromAttributes(string $className): array
    {
        try {
            $reflection  = new \ReflectionClass($className);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                return [];
            }

            $result = [];

            foreach ($constructor->getParameters() as $parameter) {
                $attributes = $parameter->getAttributes(FhirProperty::class);
                if (empty($attributes)) {
                    continue;
                }

                /** @var FhirProperty $attr */
                $attr = $attributes[0]->newInstance();

                $variants = null;
                if ($attr->isChoice && $attr->variants !== null) {
                    $variants = array_map(
                        static fn (array $v): PropertyVariantMetadata => PropertyVariantMetadata::fromArray(
                            $v['fhirType'],
                            $v['propertyKind'],
                            $v['phpType'],
                            $v['jsonKey'],
                        ),
                        $attr->variants,
                    );
                }

                $result[$parameter->getName()] = new PropertyMetadata(
                    $attr->fhirType,
                    $attr->propertyKind,
                    $attr->isArray,
                    $attr->isRequired,
                    $attr->isChoice,
                    $variants,
                    $attr->jsonKey,
                );
            }

            return $result;
        } catch (\ReflectionException) {
            return [];
        }
    }
}
