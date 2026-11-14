<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;

/**
 * Binds the FHIRPath resource variables for an invariant evaluated somewhere inside a resource.
 *
 * Shared by the invariant validators: a base type's invariant and a profile's invariant must see the
 * same `%resource` for the same node.
 *
 * @author Ardenexal
 */
final class FHIRPathResourceContext
{
    private static ?FHIRAttributeReaderInterface $sharedAttributes = null;

    /**
     * Where a node validated on its own really sits, while {@see within()} runs.
     *
     * @var \WeakMap<object, array{object, string}>|null
     */
    private static ?\WeakMap $anchors = null;

    /**
     * Resolved `[enclosing resource, container]` pairs, per validation run and then per root and path.
     *
     * @var \WeakMap<object, array<string, array{0: object, 1: object|null}>>|null
     */
    private static ?\WeakMap $resolved = null;

    /**
     * Run $validation on $node alone, with `%resource` bound as if validation had started at $root.
     *
     * A plain extension checked against a definition loaded at runtime is validated by itself, so
     * the validator's root is the extension. Its invariants must still see the resource around it,
     * as they do on the typed class reached from the resource.
     *
     * @template T
     *
     * @param object        $node       The object $validation validates
     * @param object        $root       The object validation of the whole started at
     * @param string        $path       Property path of $node under $root
     * @param \Closure(): T $validation
     *
     * @return T
     */
    public static function within(object $node, object $root, string $path, \Closure $validation): mixed
    {
        /** @var \WeakMap<object, array{object, string}> $anchors */
        $anchors        = self::$anchors ??= new \WeakMap();
        $previous       = $anchors[$node] ?? null;
        $anchors[$node] = [$root, $path];

        try {
            return $validation();
        } finally {
            // Put back what an enclosing call set for the same node
            if ($previous === null) {
                unset($anchors[$node]);
            } else {
                $anchors[$node] = $previous;
            }
        }
    }

    /**
     * The evaluation context for a node at `$path` under `$root`: `%resource` / `%rootResource` / `%context` bound to the resources around it.
     *
     * The FHIRPath engine resolves all three from `EvaluationContext::getRootResource()` and returns
     * an empty collection when it is unset. Passing no context therefore leaves them unbound, and an
     * invariant like `Reference.ref-1` —
     * `reference.startsWith('#').not() or (reference.substring(1) in %rootResource.contained.id)` —
     * evaluates its right-hand side against nothing and reports a violation for every legitimate
     * local reference.
     *
     * This was latent rather than harmless: 32 R4 invariant declarations use one of these variables,
     * and they were simply never reached, because nested elements were not traversed at all. Emitting
     * `#[Assert\Valid]` from the model generator made them reachable and turned a dead check into a
     * false-positive one — `containedToContainer`, which the Java reference validator passes with zero
     * issues, reported two `ref-1` errors.
     *
     * The binding is the **nearest enclosing resource**, not the validation root. FHIR defines
     * `%resource` as "the resource that contains the original node", and resources nest: a `Coverage`
     * inside `Parameters.parameter.resource` carries its own `contained`, and a `#payer` reference
     * within it must resolve against *that* Coverage. Binding the validation root instead made
     * `ref-1` fail on every such reference, because `Parameters` has no `contained` of its own.
     *
     * Walking the property path is what makes this possible: Symfony hands us the root object plus a
     * path like `parameter[1].resource.payor[0]`, so the deepest `#[FhirResource]` along that path is
     * the enclosing resource. Falling back to the root keeps the common top-level case unchanged.
     *
     * Every invariant on a node asks for the same path, and resolving it walks the path again from
     * the root. A caller that passes `$scope` gets that walk done once per root and path for as long
     * as the scope object lives; the invariant validators pass Symfony's execution context, which
     * lasts one `validate()` call. The model must not change while a scope is in use, which holds
     * within one validation and is why no longer-lived scope is used.
     *
     * @param object|null $scope Keys the cache of resolved paths; null resolves every time
     */
    public static function at(mixed $root, string $path, ?object $scope = null): EvaluationContext
    {
        if (!is_object($root)) {
            return new EvaluationContext(null);
        }

        $anchor = self::$anchors[$root] ?? null;
        if ($anchor !== null) {
            [$root, $prefix] = $anchor;
            $path            = $path === '' ? $prefix : "{$prefix}.{$path}";
        }

        if ($scope === null) {
            [$enclosing, $container] = self::enclosingResourceAndContainer($root, $path);
        } else {
            /** @var \WeakMap<object, array<string, array{0: object, 1: object|null}>> $resolved */
            $resolved = self::$resolved ??= new \WeakMap();
            $key      = spl_object_id($root) . '|' . $path;

            // Written in place: copying the scope's array out and back would copy every path cached so far
            $resolved[$scope]       ??= [];
            $resolved[$scope][$key] ??= self::enclosingResourceAndContainer($root, $path);

            [$enclosing, $container] = $resolved[$scope][$key];
        }

        // A new context every time: the engine sets variables on the one it is given
        return new EvaluationContext(rootResource: $enclosing, containerResource: $container);
    }

    /**
     * Walk `$path` from `$root`, returning `[nearest enclosing resource, its container]`, where the container is non-null only when
     * the enclosing resource was reached through a `contained` property.
     *
     * `%resource` is the nearest enclosing resource (M01's finding, unchanged). `%rootResource` is one
     * level out, but *only* for contained resources: FHIR defines it as "the container resource for the
     * resource that contains the original node", and a local `#id` reference inside a contained resource
     * addresses a sibling of that contained resource — so it must resolve against the container's
     * `contained`, not the contained resource's own (usually absent) one. `document-manifest` proves it:
     * its three local references all sit inside `contained[4]`, and all three reported `ref-1` against
     * the oracle's zero until `%rootResource` reached the DocumentManifest.
     *
     * The container is the **immediate** container, never the outermost root. For a node inside
     * `Bundle.entry[0].resource.contained[0]`, `%rootResource` is the entry's resource; binding the
     * Bundle instead would resolve `#id` against the wrong `contained` set and still pass
     * `document-manifest`.
     *
     * A resource reached any other way — `Parameters.parameter.resource`, `Bundle.entry.resource` — is
     * its own root, so the container stays null and `%rootResource` falls back to `%resource`. That is
     * what keeps M02's F3 closure (`containedToContainer`, whose `#payer` lives in the nested Coverage's
     * own `contained`) passing.
     *
     * Navigation is deliberately forgiving — an unreadable or absent segment simply stops the walk and
     * returns the best resource found so far, which is always at least the root. Binding a slightly
     * shallower resource degrades an invariant to its previous behaviour; throwing here would abort
     * validation of an otherwise valid document.
     *
     * @return array{0: object, 1: object|null}
     */
    private static function enclosingResourceAndContainer(object $root, string $path): array
    {
        $nearest   = $root;
        $container = null;
        $current   = $root;
        $lastProp  = null;

        foreach (self::pathSegments($path) as $segment) {
            if (!is_object($current) && !is_array($current)) {
                break;
            }

            $next = self::readSegment($current, $segment);
            if ($next === null) {
                break;
            }

            $current = $next;
            if (is_object($current) && self::isResource($current)) {
                // `contained[4]` arrives on the numeric segment, so the property navigated through is
                // the last non-numeric one; a hypothetical single-valued `contained` arrives on the
                // property segment itself. Both read the same way here.
                $arrivedVia = is_numeric($segment) ? $lastProp : $segment;
                $container  = $arrivedVia === 'contained' ? $nearest : null;
                $nearest    = $current;
            }

            if (!is_numeric($segment)) {
                $lastProp = $segment;
            }
        }

        return [$nearest, $container];
    }

    /**
     * Split `parameter[1].resource.payor[0]` into `['parameter', '1', 'resource', 'payor', '0']`.
     *
     * @return list<string>
     */
    private static function pathSegments(string $path): array
    {
        if ($path === '') {
            return [];
        }

        $normalized = str_replace(['[', ']'], ['.', ''], $path);

        return array_values(array_filter(explode('.', $normalized), static fn (string $s): bool => $s !== ''));
    }

    /**
     * One step of a property path: an array key or a public property, null when absent or unset.
     *
     * @internal shared with FHIRPathInvariantValidator, not part of the public API
     */
    public static function readSegment(mixed $current, string $segment): mixed
    {
        if (is_array($current)) {
            return $current[$segment] ?? null;
        }

        if (!is_object($current) || !property_exists($current, $segment)) {
            return null;
        }

        // Typed properties on generated models may be declared but never assigned; reading one of
        // those is an Error, not a null. See the property_exists footgun in model-object-initialization.
        // `??` reads through isset(), which answers false for an unassigned slot instead of throwing,
        // without building a ReflectionProperty on every step of every invariant's path.
        return $current->{$segment} ?? null;
    }

    /**
     * Whether this object is a FHIR resource, checking ancestors too.
     *
     * `#[FhirResource]` is not inherited by reflection, and the marker sits on both the concrete
     * class and `AbstractResource`, so the walk covers generated subclasses either way. The attribute
     * is the version-agnostic test — `AbstractResource` itself is declared once per FHIR version.
     */
    private static function isResource(object $value): bool
    {
        return self::attributes()->declaresInHierarchy($value, FhirResource::class);
    }

    /**
     * The shared attribute reader.
     *
     * This call site is static and is reached from paths that never see a constructor, so it shares
     * one reader rather than taking an injected one — the same arrangement `AbstractFHIRNormalizer`
     * uses for the identical reason. Its caches are derived from generated class shape, so two
     * instances could not disagree.
     */
    private static function attributes(): FHIRAttributeReaderInterface
    {
        return self::$sharedAttributes ??= new FHIRAttributeReader();
    }
}
