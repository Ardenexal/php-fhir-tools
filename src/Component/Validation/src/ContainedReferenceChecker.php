<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
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

    /** @var array<class-string, bool> whether the class is a resource */
    private array $isResource = [];

    /** @var array<class-string, bool> whether the class is a `Reference` */
    private array $isReference = [];

    /** @var array<class-string, string|null> the FHIR primitive type a class models, null for none */
    private array $primitiveTypes = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Report every unresolved `#id` reference and every unreferenced contained resource.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $containers = [];
        $visited    = [];
        $this->collectContainers($resource, '', false, $visited, $containers);

        $violations = [];

        foreach ($containers as $path => $container) {
            foreach ($this->checkContainer($container, (string) $path) as $violation) {
                $violations[] = $violation;
            }
        }

        return $violations;
    }

    /**
     * Find every containing resource in the tree, keyed by its path.
     *
     * A contained resource is not a container itself, but it is still walked: a contained Bundle's
     * entries are containers of their own.
     *
     * @param array<int, true>      $visited    spl_object_id keys of already-visited objects (cycle guard)
     * @param array<string, object> $containers containers found so far, keyed by path
     */
    private function collectContainers(object $node, string $path, bool $isContained, array &$visited, array &$containers): void
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return;
        }

        $visited[$id] = true;

        if (!$isContained && $this->isResource($node)) {
            $containers[$path] = $node;
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath     = $path === '' ? $name : $path . '.' . $name;
            $inContained = $name === 'contained' && $this->isResource($node);

            if (is_object($value)) {
                $this->collectContainers($value, $subPath, $inContained, $visited, $containers);
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $this->collectContainers($item, $subPath . '[' . $i . ']', $inContained, $visited, $containers);
                    }
                }
            }
        }
    }

    /**
     * Apply both rules to one containing resource.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkContainer(object $container, string $path): array
    {
        $prefix    = $path === '' ? '' : $path . '.';
        $contained = $this->contained($container);

        // Fragment Reference.reference values by path, and every `#…` value anywhere in the container.
        $references = [];
        $pointers   = [];
        $visited    = [];
        $this->collectPointers($container, $prefix, $visited, $references, $pointers);

        $ids = [];
        foreach ($contained as $item) {
            $id = $this->readString($item, 'id');

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
            $id = $this->readString($item, 'id');

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
     * Collect the `#…` values a container holds, stopping at any resource it holds other than through
     * `contained`, because that resource is a container of its own.
     *
     * @param array<int, true>      $visited    spl_object_id keys of already-visited objects (cycle guard)
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

        $path = rtrim($path, '.');

        if ($this->isReference($node)) {
            $value = $this->readString($node, 'reference');

            if ($value !== null && str_starts_with($value, '#')) {
                $references[$path] = $value;
                $pointers[$value]  = true;
            }
        } else {
            $type  = $this->primitiveType($node);
            $value = $type === null ? null : $this->readString($node, 'value');

            if ($value !== null && $type === self::XHTML) {
                $this->collectNarrativePointers($value, $pointers);
            } elseif ($value !== null && in_array($type, self::URI_TYPES, true) && str_starts_with($value, '#')) {
                $pointers[$value] = true;
            }
        }

        $isResource = $this->isResource($node);

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;
            $items   = is_array($value) ? $value : [$value];

            foreach ($items as $i => $item) {
                $itemPath = is_array($value) ? $subPath . '[' . $i . ']' : $subPath;

                if (is_string($item) && $name === 'div') {
                    $this->collectNarrativePointers($item, $pointers);
                } elseif (is_string($item) && str_starts_with($item, '#')) {
                    // A canonical held as a bare string rather than a primitive object.
                    $pointers[$item] = true;
                } elseif (is_object($item) && !$this->isOwnContainer($item, $name, $isResource)) {
                    $this->collectPointers($item, $itemPath, $visited, $references, $pointers);
                }
            }
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
        $value = $this->readPublic($container, 'contained');

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

    /** Read a string-ish property, which may be a primitive wrapper, a bare string, or absent. */
    private function readString(object $node, string $property): ?string
    {
        $value = $this->readPublic($node, $property);

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        return is_string($value) && $value !== '' ? $value : null;
    }

    /** A public property's value, or null when it is absent, non-public or never written. */
    private function readPublic(object $node, string $property): mixed
    {
        if (!in_array($property, $this->models->publicPropertyNames($node), true)) {
            return null;
        }

        if (!$this->models->isPropertyInitialized($node, $property)) {
            return null;
        }

        return $this->models->readInitializedValue($node, $property);
    }

    private function isResource(object $node): bool
    {
        return $this->isResource[$node::class] ??= $this->attributes->classAttributes($node, FhirResource::class) !== [];
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
