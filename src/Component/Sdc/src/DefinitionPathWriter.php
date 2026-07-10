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
        foreach ($this->namedTypesOf($object, $property) as $candidate) {
            if (!$candidate->isBuiltin() && class_exists($candidate->getName())) {
                return $candidate->getName();
            }
        }

        return null;
    }

    /**
     * The declared PHP types of a property (a single named type, or each member of a union type).
     *
     * @return list<\ReflectionNamedType>
     */
    private function namedTypesOf(object $object, string $property): array
    {
        if (!property_exists($object, $property)) {
            return [];
        }

        $type = (new \ReflectionProperty($object, $property))->getType();
        if ($type instanceof \ReflectionNamedType) {
            return [$type];
        }
        if ($type instanceof \ReflectionUnionType) {
            return array_values(array_filter(
                $type->getTypes(),
                static fn (\ReflectionType $t): bool => $t instanceof \ReflectionNamedType,
            ));
        }

        return [];
    }

    /**
     * Coerce a raw scalar (a calculated `definitionExtractValue` result) into the primitive wrapper its
     * target property declares, so a computed `'http://…'` lands in a `?UriPrimitive` etc. Values that
     * are already objects, or scalars the declared type accepts directly (e.g. `StringPrimitive|string`),
     * pass through untouched — so pre-wrapped answers are never disturbed.
     */
    private function coerceScalar(mixed $value, string $targetClass): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        if (!class_exists($targetClass) || !$this->constructorAcceptsValue($targetClass)) {
            return $value;
        }

        return new $targetClass(value: $value);
    }

    /**
     * Given a property's declared types, resolve the primitive class to wrap a raw scalar into, or null
     * when the declaration already accepts the scalar directly (a builtin match) or offers no wrapper.
     */
    private function primitiveWrapperFor(object $current, string $property, mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $named = $this->namedTypesOf($current, $property);

        foreach ($named as $type) {
            if ($type->isBuiltin() && $this->scalarMatchesBuiltin($value, $type->getName())) {
                return null; // declared type accepts the raw scalar as-is
            }
        }

        foreach ($named as $type) {
            $name = $type->getName();
            if (!$type->isBuiltin() && class_exists($name) && $this->constructorAcceptsValue($name)) {
                return $name;
            }
        }

        return null;
    }

    private function scalarMatchesBuiltin(mixed $value, string $type): bool
    {
        return match ($type) {
            'string' => is_string($value),
            'int'    => is_int($value),
            'float'  => is_float($value) || is_int($value),
            'bool'   => is_bool($value),
            'mixed'  => true,
            default  => false,
        };
    }

    /**
     * @param class-string $class
     */
    private function constructorAcceptsValue(string $class): bool
    {
        $constructor = (new \ReflectionClass($class))->getConstructor();
        if ($constructor === null) {
            return false;
        }

        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->getName() === 'value') {
                return true;
            }
        }

        return false;
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
            $list[]             = $meta->phpItemClass !== null ? $this->coerceScalar($value, $meta->phpItemClass) : $value;
            $current->$property = $list;

            return;
        }

        // A bare builtin-scalar leaf (e.g. `Resource.id` is `?string`, modeled as System.String rather
        // than a primitive wrapper) cannot hold a `StringPrimitive` answer object from the deserializer;
        // unwrap it to its scalar first (the inverse of the wrap coercion below).
        $value = $this->unwrapForBuiltinLeaf($current, $property, $value);

        $wrapper            = $this->primitiveWrapperFor($current, $property, $value);
        $current->$property = $wrapper !== null ? $this->coerceScalar($value, $wrapper) : $value;
    }

    /**
     * When a leaf's declared type is a bare builtin scalar but the incoming answer is a primitive-wrapper
     * object (a `StringPrimitive`/`CodePrimitive`/… from the deserializer), unwrap it to its inner scalar
     * so the assignment type-checks — the inverse of {@see coerceScalar}. Only applies when NO declared
     * type accepts the wrapper object, so union leaves like `StringPrimitive|string` keep their value
     * untouched.
     */
    private function unwrapForBuiltinLeaf(object $current, string $property, mixed $value): mixed
    {
        if (!is_object($value) || !property_exists($value, 'value')) {
            return $value;
        }

        $named = $this->namedTypesOf($current, $property);

        foreach ($named as $type) {
            $name = $type->getName();
            if (!$type->isBuiltin() && $value instanceof $name) {
                return $value; // the property accepts the wrapper object as declared — leave it wrapped
            }
        }

        // `??` reads an uninitialized typed `value` (deserializer-origin) as absent rather than throwing.
        $inner = $value->value ?? null;
        foreach ($named as $type) {
            if ($type->isBuiltin() && $this->scalarMatchesBuiltin($inner, $type->getName())) {
                return $inner;
            }
        }

        return $value;
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
