<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Resolves and caches PropertyMetadata for FHIR model classes.
 *
 * Resolution order per class:
 *  1. L1: in-process array cache (keyed by class-string)
 *  2. L2: PSR-6 cache pool (if configured via Symfony bundle)
 *  3. L3: #[FhirProperty] attribute reflection — once per class per process
 *
 * @author Ardenexal
 */
class PropertyMetadataProvider implements PropertyMetadataProviderInterface
{
    /** @var array<class-string, array<string, PropertyMetadata>> */
    private array $cache = [];

    public function __construct(
        private ?CacheItemPoolInterface $psrCache = null,
    ) {
    }

    /**
     * Returns the canonical PSR-6 cache key for a FHIR model class.
     */
    public static function cacheKey(string $className): string
    {
        return 'fhir.property_metadata.' . hash('sha256', $className);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyMetadata(string $className): array
    {
        // L1: in-process cache
        if (isset($this->cache[$className])) {
            return $this->cache[$className];
        }

        // L2: PSR-6 (if configured)
        if ($this->psrCache !== null) {
            $item = $this->psrCache->getItem(self::cacheKey($className));
            if ($item->isHit()) {
                $value = $item->get();
                if (is_array($value)) {
                    /** @var array<string, PropertyMetadata> $value */
                    $this->cache[$className] = $value;

                    return $value;
                }
            }
        }

        // L3: reflection on #[FhirProperty] attributes
        $metadata = $this->resolveFromAttributes($className);

        // Write-through: populate L1 and L2 on miss
        $this->cache[$className] = $metadata;
        if ($this->psrCache !== null && $metadata !== []) {
            $item = $this->psrCache->getItem(self::cacheKey($className));
            $item->set($metadata);
            $this->psrCache->save($item);
        }

        return $metadata;
    }

    /**
     * Resolve metadata by reflecting #[FhirProperty] attributes on constructor parameters.
     *
     * Walks the full class hierarchy (child → parent) so that typed IG subclasses — which
     * only declare new properties in their own constructor — still inherit metadata for
     * all parameters defined in ancestor constructors. Child parameters override parent
     * parameters with the same name.
     *
     * @param class-string $className
     *
     * @return array<string, PropertyMetadata>
     */
    private function resolveFromAttributes(string $className): array
    {
        try {
            // Collect the class hierarchy from root → child so child params win on merge.
            $classes  = [];
            $current  = new \ReflectionClass($className);
            while ($current !== false) {
                $classes[] = $current;
                $current   = $current->getParentClass();
            }
            $classes = array_reverse($classes); // root first

            $result           = [];
            $seenConstructors = [];

            foreach ($classes as $class) {
                $constructor = $class->getConstructor();
                if ($constructor === null) {
                    continue;
                }

                // Skip constructors that were already processed (inherited without override).
                $constructorKey = $constructor->getDeclaringClass()->getName();
                if (isset($seenConstructors[$constructorKey])) {
                    continue;
                }
                $seenConstructors[$constructorKey] = true;

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

                    // Child param silently overwrites a parent param of the same name.
                    $result[$parameter->getName()] = new PropertyMetadata(
                        $attr->fhirType,
                        $attr->propertyKind,
                        $attr->isArray,
                        $attr->isRequired,
                        $attr->isChoice,
                        $variants,
                        $attr->jsonKey,
                        $attr->phpType,
                        $attr->xmlSerializedName,
                    );
                }
            }

            return $result;
        } catch (\ReflectionException) {
            return [];
        }
    }
}
