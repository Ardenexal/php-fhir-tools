<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProviderInterface;

/**
 * Writes answer values into typed generated FHIR resources by canonical `definition` path, sourcing
 * cardinality/type/choice-variant purely from `#[FhirProperty]` metadata (via the same
 * {@see PropertyMetadataProviderInterface} the serializer uses) — **no per-resource-type code**.
 *
 * Promoted from M01's proven `SpikeDefinitionPathWriter`. Two primitives support **hierarchical**
 * definition-based extraction (an SDC Questionnaire's item grouping determines element merging):
 *
 *  - {@see writeLeaf()} writes a value at a relative property path, **reusing** array intermediates
 *    (so several leaves under one group land in the same element instance);
 *  - {@see createIntermediate()} appends a **new** intermediate instance and returns it (so a group
 *    item establishes exactly one `name`/`contact`/… element that its children then populate).
 *
 * Segments are PHP property names relative to a context object; a trailing choice marker (`value[x]`)
 * is normalised to the backing property (`value`). A flat `definition` carries no array index and no
 * concrete choice type, so append-vs-set and the polymorphic variant are decided from metadata.
 */
final class DefinitionPathWriter
{
    public function __construct(
        private readonly PropertyMetadataProviderInterface $provider,
    ) {
    }

    /**
     * Write $value into $target at the relative property path $segments, reusing existing intermediates.
     *
     * @param non-empty-list<string> $segments property names relative to $target (e.g. ['given'] or ['name', 'given'])
     */
    public function writeLeaf(object $target, array $segments, mixed $value): void
    {
        $current = $target;
        $last    = count($segments) - 1;

        foreach ($segments as $i => $segment) {
            $property = $this->normalizeSegment($segment);
            if ($i === $last) {
                $this->setLeaf($current, $property, $value);

                return;
            }
            $current = $this->descend($current, $property, reuseExisting: true);
        }
    }

    /**
     * Create and append a NEW intermediate element at the relative path $segments; return it.
     *
     * Intermediates *before* the final segment are reused; only the final segment is freshly appended,
     * so a repeating group item yields one new element instance per invocation.
     *
     * @param non-empty-list<string> $segments property names relative to $target
     */
    public function createIntermediate(object $target, array $segments): object
    {
        $current = $target;
        $last    = count($segments) - 1;

        foreach ($segments as $i => $segment) {
            $property = $this->normalizeSegment($segment);
            $current  = $this->descend($current, $property, reuseExisting: $i !== $last);
        }

        return $current;
    }

    private function metaFor(object $object, string $property): PropertyMetadata
    {
        $map = $this->provider->getPropertyMetadata($object::class);

        return $map[$property]
            ?? throw new \RuntimeException(\sprintf('No #[FhirProperty] metadata for %s::$%s', $object::class, $property));
    }

    /**
     * Navigate into (or instantiate) an intermediate element property.
     *
     * For array properties, `reuseExisting` reuses the first element when present; otherwise a fresh
     * instance is appended. For single-valued complex properties the existing value is reused.
     */
    private function descend(object $current, string $property, bool $reuseExisting): object
    {
        $meta = $this->metaFor($current, $property);
        // `phpItemClass` carries the item class for array-valued complex properties, but the generated
        // models leave it null for single-valued complex ones (e.g. `RelatedPerson.patient`). Fall back
        // to the property's declared PHP type so a scalar-holding intermediate (a `Reference` whose
        // `.reference` we set) can still be instantiated.
        $class = $meta->phpItemClass
            ?? $this->reflectPropertyClass($current, $property)
            ?? throw new \RuntimeException(\sprintf('Intermediate %s::$%s has no phpType to instantiate', $current::class, $property));

        if ($meta->isArray) {
            $list = $this->readArray($current, $property);
            if ($reuseExisting && ($list[0] ?? null) instanceof $class) {
                return $list[0];
            }
            $child              = new $class();
            $list[]             = $child;
            $current->$property = $list;

            return $child;
        }

        $child = $current->$property ?? null;
        if (!$child instanceof $class) {
            $child              = new $class();
            $current->$property = $child;
        }

        return $child;
    }

    /**
     * Resolve the concrete class of a single-valued complex property from its PHP type declaration,
     * used when `#[FhirProperty]` metadata carries no `phpItemClass` (single-valued complex elements).
     *
     * @return class-string|null the first non-builtin declared type that is an instantiable class, or null
     */
    private function reflectPropertyClass(object $object, string $property): ?string
    {
        if (!property_exists($object, $property)) {
            return null;
        }

        $type = (new \ReflectionProperty($object, $property))->getType();
        if ($type instanceof \ReflectionNamedType) {
            $candidates = [$type];
        } elseif ($type instanceof \ReflectionUnionType) {
            $candidates = $type->getTypes();
        } else {
            return null;
        }

        foreach ($candidates as $candidate) {
            if ($candidate instanceof \ReflectionNamedType && !$candidate->isBuiltin() && class_exists($candidate->getName())) {
                return $candidate->getName();
            }
        }

        return null;
    }

    private function setLeaf(object $current, string $property, mixed $value): void
    {
        $meta = $this->metaFor($current, $property);

        if ($meta->isChoice) {
            // Resolve which polymorphic variant this value is (metadata-driven); the single backing
            // property holds the union, so the assignment itself is type-agnostic.
            $this->resolveChoiceVariant($meta, $value);
            $current->$property = $value;

            return;
        }

        if ($meta->isArray) {
            $list               = $this->readArray($current, $property);
            $list[]             = $value;
            $current->$property = $list;

            return;
        }

        $current->$property = $value;
    }

    /**
     * @return array<int, mixed>
     */
    private function readArray(object $current, string $property): array
    {
        // `??` uses isset() semantics: an uninitialized typed property (deserializer-origin object)
        // reads as absent instead of throwing \Error — the model-init footgun, handled generically.
        $value = $current->$property ?? [];

        return is_array($value) ? array_values($value) : [];
    }

    private function resolveChoiceVariant(PropertyMetadata $meta, mixed $value): void
    {
        foreach ($meta->variants ?? [] as $variant) {
            if ($this->valueMatchesType($value, $variant->phpType)) {
                return;
            }
        }

        throw new \RuntimeException(\sprintf('No choice variant matches value of type %s', get_debug_type($value)));
    }

    private function valueMatchesType(mixed $value, string $phpType): bool
    {
        return match ($phpType) {
            'bool'   => is_bool($value),
            'int'    => is_int($value),
            'float'  => is_float($value),
            'string' => is_string($value),
            default  => $value instanceof $phpType,
        };
    }

    /**
     * Strip a choice element's `[x]` marker so it maps to the backing PHP property (`value[x]` → `value`).
     */
    private function normalizeSegment(string $segment): string
    {
        return str_ends_with($segment, '[x]') ? substr($segment, 0, -3) : $segment;
    }
}
