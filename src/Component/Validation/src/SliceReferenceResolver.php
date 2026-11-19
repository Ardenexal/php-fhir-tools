<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;

/**
 * Resolves the references of a sliced element to the resources they point at, for discriminator
 * paths that go through `resolve()`.
 *
 * A reference is looked up, in order:
 *  1. **Contained.** A local `#id` reference names a resource in the `contained` of the resource
 *     holding it. Inside a contained resource it names a sibling, in the container's `contained`, or
 *     the container itself for a bare `#`.
 *  2. **Bundle.** Inside `Bundle.entry[n].resource`, a relative or absolute reference names the entry
 *     it resolves to by the rules of {@see BundleEntryIndex}, when exactly one does.
 *  3. **The configured resolver.** Anything else goes to the {@see FHIRReferenceResolverInterface};
 *     the default one resolves nothing.
 *
 * Null means the reference could not be resolved, which leaves the item unjudged rather than outside
 * the slice.
 *
 * Built for one sliced element in one validation, because model properties are mutable: the resources
 * around the element are read once, from the validation root along the element's property path.
 *
 * @internal used by FHIRSliceConstraintValidator, not part of the public API
 *
 * @author Ardenexal
 */
final class SliceReferenceResolver
{
    /** The nearest resource holding the sliced element */
    private readonly ?object $nearest;

    /** The container of the nearest enclosing resource, when that resource is contained */
    private readonly ?object $container;

    /** The nearest Bundle holding the sliced element in one of its entries, and that entry's position */
    private readonly ?object $bundle;

    private readonly int $entryIndex;

    private ?BundleEntryIndex $index = null;

    /**
     * @param object $root The object validation started at
     * @param string $path Property path from $root to the object holding the sliced element, e.g. `entry[1].resource`
     */
    public function __construct(
        object $root,
        string $path,
        private readonly FHIRReferenceResolverInterface $resolver = new NullFHIRReferenceResolver(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
        $nearest    = self::isResource($root) ? $root : null;
        $container  = null;
        $bundle     = null;
        $entryIndex = -1;

        /** @var list<array{string, mixed}> $trail Each segment walked, with the node it was read from */
        $trail   = [];
        $current = $root;

        foreach (preg_split('/[.\[\]]+/', $path, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $segment) {
            $next = FHIRPathResourceContext::readSegment($current, $segment);
            if ($next === null) {
                break;
            }

            $trail[] = [$segment, $current];
            $current = $next;

            if (!is_object($current) || !self::isResource($current)) {
                continue;
            }

            // `contained[2]`: the property is the segment before the index
            $via       = is_numeric($segment) ? ($trail[count($trail) - 2][0] ?? null) : $segment;
            $container = $via === 'contained' ? $nearest : null;

            // `entry[1].resource`: the Bundle is the node `entry` was read from
            $count = count($trail);
            if ($segment === 'resource' && $count >= 3 && $trail[$count - 3][0] === 'entry' && is_numeric($trail[$count - 2][0])) {
                $holder = $trail[$count - 3][1];
                if (is_object($holder) && self::typeOf($holder) === 'Bundle') {
                    $bundle     = $holder;
                    $entryIndex = (int) $trail[$count - 2][0];
                }
            }

            $nearest = $current;
        }

        $this->nearest    = $nearest;
        $this->container  = $container;
        $this->bundle     = $bundle;
        $this->entryIndex = $entryIndex;
    }

    /**
     * The resource a Reference points at, or null when it cannot be told.
     */
    public function resolve(object $reference): ?object
    {
        $value = self::scalar(FHIRPathResourceContext::readSegment($reference, 'reference'));

        if ($value !== null && str_starts_with($value, '#')) {
            return $this->resolveContained(substr($value, 1));
        }

        if ($value !== null && $this->bundle !== null) {
            $resolved = $this->resolveInBundle($value);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        return $this->resolver->resolve($reference);
    }

    /**
     * The contained resource with this id, looked up where a local reference points.
     */
    private function resolveContained(string $id): ?object
    {
        // Inside a contained resource, `#` is the container and `#x` a sibling
        $holder = $this->container ?? $this->nearest;
        if ($id === '') {
            return $this->container;
        }

        $contained = $holder === null ? null : FHIRPathResourceContext::readSegment($holder, 'contained');
        foreach (is_array($contained) ? $contained : [] as $resource) {
            if (is_object($resource) && self::scalar(FHIRPathResourceContext::readSegment($resource, 'id')) === $id) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * The resource of the one entry a reference made inside a Bundle entry resolves to.
     */
    private function resolveInBundle(string $reference): ?object
    {
        if ($this->bundle === null) {
            return null;
        }

        $this->index ??= (new BundleEntryReader(new FHIRModelAccessor(), $this->attributes))->index($this->bundle);

        if (BundleEntryIndex::isAbsolute($reference)) {
            $matches = $this->index->resolveAbsolute($reference);
        } else {
            $parts   = BundleEntryIndex::parseRelative($reference);
            $matches = $parts === null ? [] : $this->index->resolveRelative($parts['type'], $parts['id'], $parts['version'], $this->entryIndex);
        }

        return count($matches) === 1 ? ($this->index->entries[$matches[0]]['resource'] ?? null) : null;
    }

    private static function scalar(mixed $value): ?string
    {
        return match (true) {
            is_string($value), $value instanceof \Stringable => (string) $value,
            default                                          => null,
        };
    }

    private static function isResource(object $value): bool
    {
        return self::typeOf($value) !== null;
    }

    /**
     * The resource type of an object, or null when it is not a resource. `#[FhirResource]` is read up
     * the class hierarchy, since a profile class extends the resource class that carries it.
     */
    private static function typeOf(object $value): ?string
    {
        for ($class = new \ReflectionClass($value); $class !== false; $class = $class->getParentClass()) {
            $attribute = $class->getAttributes(FhirResource::class)[0] ?? null;
            if ($attribute !== null) {
                return $attribute->newInstance()->type;
            }
        }

        return null;
    }
}
