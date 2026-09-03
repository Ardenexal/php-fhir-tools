<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

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

    /** Answers whether a class is a FHIR model at all, which the property map cannot. */
    private FHIRStructureKindProviderInterface $structureKinds;

    public function __construct(
        private ?CacheItemPoolInterface $psrCache = null,
        ?FHIRStructureKindProviderInterface $structureKinds = null,
    ) {
        $this->structureKinds = $structureKinds ?? new FHIRStructureKindProvider();
    }

    /**
     * Identifies the shape of the cached payload, not its content.
     *
     * A warm PSR-6 pool outlives a deployment, and the entries it holds are serialized objects whose
     * class names are part of that payload. The read guard is a bare array check, so a pool warmed
     * before this component moved namespaces would be served straight back as the old shape rather
     * than rejected. Bump this whenever the cached structure changes -- a namespace move counts.
     */
    private const string CACHE_SCHEMA = 'metadata-type-v2';

    /**
     * Returns the canonical PSR-6 cache key for a FHIR model class.
     *
     * The schema token is part of the key rather than part of the value, so entries written by an
     * older shape become unreachable instead of being read back and misinterpreted.
     */
    public static function cacheKey(string $className): string
    {
        return 'fhir.property_metadata.' . self::CACHE_SCHEMA . '.' . hash('sha256', $className);
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
     * {@inheritDoc}
     */
    public function isFhirModelClass(string $className): bool
    {
        // Read the structural marker, not the size of the property map. The map answers the empty
        // array for three different situations, and testing it for emptiness -- which this method
        // used to do -- collapses exactly the distinction the interface promises callers: a FHIR
        // model that happens to declare no properties reported as "not a FHIR model". Every
        // generated model declares at least one property today, so the two agreed in practice; they
        // would stop agreeing the first time one did not, and silently.
        return $this->structureKinds->inheritedKindOf($className) !== null;
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

                    // Build variants for value[x] choices (isChoice) AND for transparent
                    // xml-choice-group properties (propertyKind 'choiceGroup'), which reuse the
                    // same per-variant shape keyed by child element name. value[x] semantics are
                    // unchanged; choiceGroup keeps isChoice false (see FhirProperty propertyKind doc).
                    $variants = null;
                    if ($attr->variants !== null && ($attr->isChoice || $attr->propertyKind === 'choiceGroup')) {
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
                        $attr->xmlNamespace,
                    );
                }
            }

            return $result;
        } catch (\ReflectionException) {
            return [];
        }
    }
}
