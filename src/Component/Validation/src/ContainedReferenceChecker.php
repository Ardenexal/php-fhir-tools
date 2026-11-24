<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Checks the `#id` links between a resource and the resources it contains, in both directions.
 *
 * 1. A `Reference.reference` of `#id` must name a resource in the containing resource's `contained`.
 *    The reference validator reports `Unable to resolve resource with reference '#id'` beside the
 *    `ref-1` invariant, which says only that *some* contained resource must exist.
 * 2. Every contained resource must be referred to from elsewhere in the containing resource, or must
 *    itself refer to the container with `#`. This is invariant `dom-3`, reported once per contained
 *    resource on `contained[n]`, which is how the reference validator reports it.
 *
 * ## Why dom-3 is checked here rather than by its FHIRPath expression
 *
 * The published expression cannot answer the question in three places the corpus exercises:
 *
 *  - It reports one finding on the resource as a whole, and does not say which contained resource is
 *    unreferenced. `R5.list-contained-bad` reports it on `List.contained[0]`.
 *  - A contained resource with no id makes `'#' + id` empty, the `where()` drops it, and the expression
 *    passes. `R5.patient-duplicate-contained` holds one, and the reference validator reports it.
 *  - The R4 and R4B expression uses `as(canonical)` on a collection, which the engine rejects, so it
 *    never reports at all (`contained-resource-bad-id`, `bundle-ea-testcase`).
 *
 * The narrative counts as "elsewhere", as it does for the reference validator: an `<img src="#pic1"/>`
 * is the spec's own example of a contained Binary that nothing else points at. The generated `dom-3`
 * constraint keeps working for callers that run the Symfony validator on its own;
 * {@see FHIRValidationService} drops its finding and reports this one instead.
 *
 * ## What is a containing resource
 *
 * The root, and every resource reached other than through `contained`: each Bundle entry's resource,
 * a `Parameters` resource value, and the entries of a Bundle that is itself contained. A fragment never
 * resolves across that boundary, so `#p` in one Bundle entry cannot reach another entry's `contained`.
 */
final class ContainedReferenceChecker
{
    private const string REFERENCE = 'Reference';

    /** Primitive types whose value can be a `#id` pointer to a contained resource. */
    private const array URI_TYPES = ['canonical', 'uri', 'url'];

    /** Narrative markup, scanned for `src` and `href` fragments. */
    private const string XHTML = 'xhtml';

    /** `src` and `href` attributes in narrative markup whose value is a fragment. */
    private const string NARRATIVE_FRAGMENT = '/(?:src|href)\s*=\s*(["\'])#([^"\'\s>]+)\1/i';

    /** @var array<class-string, bool> whether the class is a `Reference` */
    private array $isReference = [];

    /** @var array<class-string, string|null> the FHIR primitive type a class models, null for none */
    private array $primitiveTypes = [];

    private readonly BundleEntryReader $reader;

    public function __construct(
        FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
        $this->reader = new BundleEntryReader($models, $attributes);
    }

    /**
     * Report every unresolved `#id` reference and every unreferenced contained resource.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $walk = new ContainedReferenceWalk();
        $this->walk($resource, '', null, false, $walk);

        $violations = [];

        foreach ($walk->containers as $index => [$path, $container]) {
            foreach ($this->checkContainer($container, $path, $walk->references[$index], $walk->pointers[$index]) as $violation) {
                $violations[] = $violation;
            }
        }

        return $violations;
    }

    /**
     * Find every containing resource and collect each one's `#…` values, in one pass over the tree.
     *
     * This does in one walk what {@see collectPointers()} would do once per container, after a
     * separate walk to find the containers. A resource reached other than through its parent's
     * `contained` starts a new container; a contained resource stays in its container's walk, but
     * is still walked for containers of its own, so a contained Bundle's entries are containers.
     *
     * An object reachable from more than one place is searched for containers once, tracked in
     * `$walk->discovered`, but has its values collected once per container, tracked in `$walk->collected`,
     * so the findings match the two separate walks.
     *
     * @param int|null $container index of the container being collected, null above the first one
     */
    private function walk(object $node, string $path, ?int $container, bool $isContained, ContainedReferenceWalk $walk): void
    {
        $id       = spl_object_id($node);
        $discover = !isset($walk->discovered[$id]);

        if (!$isContained && $this->isResource($node)) {
            if (!$discover) {
                // Met before, where its place as a container or a contained resource was settled. Either
                // way its values do not belong to the container being collected.
                return;
            }

            $container                      = count($walk->containers);
            $walk->containers[]             = [$path, $node];
            $walk->references[$container]   = [];
            $walk->pointers[$container]     = [];
        }

        if (!$discover) {
            // Reached again from elsewhere in a container: collect its values for this container
            // too, as a walk of that container alone would, but find no containers a second time.
            if ($container !== null) {
                $walk->collected[$container] ??= [];
                $this->collectPointers($node, $path, $walk->collected[$container], $walk->references[$container], $walk->pointers[$container]);
            }

            return;
        }

        $walk->discovered[$id] = true;
        $collect               = $container !== null && !isset($walk->collected[$container][$id]);

        if ($collect) {
            $walk->collected[$container][$id] = true;
            $this->collectNodePointers($node, $path, $walk->references[$container], $walk->pointers[$container]);
        }

        $isResource = $this->isResource($node);

        foreach ($this->reader->children($node) as $name => $value) {
            $subPath     = $path === '' ? $name : $path . '.' . $name;
            $inContained = $name === 'contained' && $isResource;
            $items       = is_array($value) ? $value : [$value];

            foreach ($items as $i => $item) {
                if (is_object($item)) {
                    $this->walk($item, is_array($value) ? $subPath . '[' . $i . ']' : $subPath, $container, $inContained, $walk);
                } elseif ($collect && is_string($item)) {
                    $this->collectStringPointers($item, $name, $walk->pointers[$container]);
                }
            }
        }
    }

    /**
     * Apply both rules to one containing resource.
     *
     * @param array<string, string> $references fragment `Reference.reference` values, keyed by path
     * @param array<string, true>   $pointers   every fragment value in the container, as a set
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkContainer(object $container, string $path, array $references, array $pointers): array
    {
        $prefix    = $path === '' ? '' : $path . '.';
        $contained = $this->contained($container);

        $ids = [];
        foreach ($contained as $item) {
            $id = $this->reader->readString($item, 'id');

            if ($id !== null) {
                $ids[$id] = true;
            }
        }

        $violations = [];

        foreach ($references as $referencePath => $reference) {
            if ($reference !== '#' && !isset($ids[substr($reference, 1)])) {
                $violations[] = $this->violation(
                    (string) $referencePath,
                    sprintf("Unable to resolve resource with reference '%s'", $reference),
                    null,
                );
            }
        }

        foreach ($contained as $index => $item) {
            $id = $this->reader->readString($item, 'id');

            if (($id !== null && isset($pointers['#' . $id])) || $this->refersToContainer($item)) {
                continue;
            }

            $violations[] = $this->violation(
                $prefix . 'contained[' . $index . ']',
                sprintf(
                    "The contained resource '%s' is not referenced to from elsewhere in the containing resource nor does it refer to the containing resource (dom-3)",
                    $id ?? '(no id)',
                ),
                'dom-3',
            );
        }

        return $violations;
    }

    /**
     * Collect the `#…` values held at or under a node, stopping at any resource held other than through
     * `contained`, because that resource is a container of its own.
     *
     * @param array<int, true>      $visited    spl_object_id keys of objects already collected into these lists (cycle guard)
     * @param array<string, string> $references fragment `Reference.reference` values, keyed by path
     * @param array<string, true>   $pointers   every fragment value found, as a set
     */
    private function collectPointers(object $node, string $path, array &$visited, array &$references, array &$pointers): void
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return;
        }

        $visited[$id] = true;

        $this->collectNodePointers($node, $path, $references, $pointers);

        $isResource = $this->isResource($node);

        foreach ($this->reader->children($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;
            $items   = is_array($value) ? $value : [$value];

            foreach ($items as $i => $item) {
                if (is_string($item)) {
                    $this->collectStringPointers($item, $name, $pointers);
                } elseif (is_object($item) && !$this->isOwnContainer($item, $name, $isResource)) {
                    $this->collectPointers($item, is_array($value) ? $subPath . '[' . $i . ']' : $subPath, $visited, $references, $pointers);
                }
            }
        }
    }

    /**
     * Collect the `#…` value a node itself holds: a `Reference.reference`, a `uri`-like primitive's
     * value, or the fragments a narrative's markup links to.
     *
     * @param array<string, string> $references fragment `Reference.reference` values, keyed by path
     * @param array<string, true>   $pointers   every fragment value found, as a set
     */
    private function collectNodePointers(object $node, string $path, array &$references, array &$pointers): void
    {
        if ($this->isReference($node)) {
            $value = $this->reader->readString($node, 'reference');

            if ($value !== null && str_starts_with($value, '#')) {
                $references[$path] = $value;
                $pointers[$value]  = true;
            }

            return;
        }

        $type = $this->primitiveType($node);

        // Only narrative and uri-like primitives can point at a contained resource, so no other
        // primitive's value is read.
        if ($type === self::XHTML) {
            $value = $this->reader->readString($node, 'value');

            if ($value !== null) {
                $this->collectNarrativePointers($value, $pointers);
            }
        } elseif ($type !== null && in_array($type, self::URI_TYPES, true)) {
            $value = $this->reader->readString($node, 'value');

            if ($value !== null && str_starts_with($value, '#')) {
                $pointers[$value] = true;
            }
        }
    }

    /**
     * Collect the `#…` values in a bare string property: narrative markup held as a string `div`, or
     * a canonical held as a bare string rather than a primitive object.
     *
     * @param array<string, true> $pointers
     */
    private function collectStringPointers(string $value, string $property, array &$pointers): void
    {
        if ($property === 'div') {
            $this->collectNarrativePointers($value, $pointers);
        } elseif (str_starts_with($value, '#')) {
            $pointers[$value] = true;
        }
    }

    /**
     * Add the fragments narrative markup points at with `src` or `href`, wherever the narrative sits:
     * `binary-ref-internal` points at its contained Binary from a Composition section's narrative.
     *
     * @param array<string, true> $pointers
     */
    private function collectNarrativePointers(string $markup, array &$pointers): void
    {
        if (preg_match_all(self::NARRATIVE_FRAGMENT, $markup, $matches) > 0) {
            foreach ($matches[2] as $fragment) {
                $pointers['#' . $fragment] = true;
            }
        }
    }

    /** A resource reached other than through its parent's `contained` is checked on its own. */
    private function isOwnContainer(object $item, string $property, bool $parentIsResource): bool
    {
        return $this->isResource($item) && !($property === 'contained' && $parentIsResource);
    }

    /** Whether a contained resource holds a reference of exactly `#`, which names its container. */
    private function refersToContainer(object $contained): bool
    {
        $references = [];
        $pointers   = [];
        $visited    = [];
        $this->collectPointers($contained, '', $visited, $references, $pointers);

        return isset($pointers['#']);
    }

    /** @return list<object> the container's contained resources, in order */
    private function contained(object $container): array
    {
        $value = $this->reader->readPublic($container, 'contained');

        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, 'is_object'));
    }

    private function violation(string $path, string $message, ?string $invariantKey): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: $invariantKey,
        );
    }

    private function isResource(object $node): bool
    {
        return $this->reader->resourceType($node) !== null;
    }

    private function isReference(object $node): bool
    {
        if (!isset($this->isReference[$node::class])) {
            $attributes                        = $this->attributes->classAttributes($node, FHIRComplexType::class);
            $this->isReference[$node::class]   = $attributes !== [] && $attributes[0]->typeName === self::REFERENCE;
        }

        return $this->isReference[$node::class];
    }

    private function primitiveType(object $node): ?string
    {
        if (!array_key_exists($node::class, $this->primitiveTypes)) {
            $attributes                          = $this->attributes->classAttributes($node, FHIRPrimitive::class);
            $this->primitiveTypes[$node::class]  = $attributes === [] ? null : $attributes[0]->primitiveType;
        }

        return $this->primitiveTypes[$node::class];
    }
}
