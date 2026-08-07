<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Operation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistryFactory;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProviderInterface;

/**
 * Maps typed operation payloads to and from a `Parameters` resource.
 *
 * One mapper serves every FHIR version. That is the load-bearing claim of the whole operation
 * code-generation effort: the versions differ in the *metadata* attached to generated classes, not
 * in the logic that reads it. There is deliberately no `match ($version)` and no version literal
 * anywhere below.
 *
 * ## Every class comes from the registry, never from a built namespace
 *
 * The mapper resolves `Parameters` through {@see FHIRTypeResolverInterface}, then reads that
 * class's own `#[FhirProperty]` metadata to learn its backbone type and its `value[x]` variants.
 * Nothing is assembled by string-concatenating `Models\{version}\...`.
 *
 * That matters beyond tidiness: a hardcoded namespace can only ever produce base-spec classes. An
 * Implementation Guide that profiles `Parameters`, or an application that calls
 * `addResourceTypeMapping()`, would be silently ignored — the mapper would build the base class
 * while the rest of the serializer honoured the profile. Going through the resolver means a
 * profiled `Parameters`, and profiled resources in the `resource` slot, are produced correctly.
 *
 * ## Which value goes in which slot
 *
 * `ParametersParameter` enforces invariant `inv-1`: a parameter has exactly one of `value`,
 * `resource`, or `part`. This mapper picks between them from metadata alone:
 *
 *  - `partClass` set          → `part[]`, recursing into the nested payload class.
 *  - `type` names a resource  → `resource`, the whole resource unwrapped.
 *  - otherwise                → `value[x]`.
 *
 * ## Primitives are wrapped here, not on the payload
 *
 * Operation payload classes type primitives as bare PHP values (`?string $code`) because they are
 * ergonomic DTOs. `Parameters` cannot carry a bare string for most types: the serializer resolves
 * `value[x]` by matching the runtime type against the choice variants, so `'en'` alone is
 * indistinguishable between `valueCode`, `valueString` and half a dozen others. The mapper therefore
 * wraps on the way out and unwraps on the way back.
 *
 * **Known limitation, accepted:** a bare `?string` cannot carry primitive extensions. A response
 * whose `valueCode` has a sibling `_valueCode` loses that sibling on the way into the typed object,
 * so such a payload does not round-trip byte-for-byte. Polymorphic parameters are unaffected —
 * they hold real primitive wrappers, which do carry extensions.
 *
 * ## Polymorphic parameters take typed values
 *
 * Where a parameter declares `variants`, the mapper cannot infer intent from a bare scalar and does
 * not try: `$value = 'active'` could be `valueCode` or `valueString`. Callers pass the wrapper
 * (`new CodePrimitive(value: 'active')`) or a complex type, and the mapper passes it through for
 * the serializer's own variant resolution. Booleans and integers are unambiguous and are allowed
 * bare.
 *
 * @author Ardenexal
 */
final class OperationParameterMapper
{
    public function __construct(
        /**
         * Resolves `Parameters` and resource-typed parameters to concrete classes.
         *
         * Version-scoped at construction, exactly like the normalizers. Going through the resolver
         * rather than building namespaces by hand is what lets a profiled `Parameters` — registered
         * via `addResourceTypeMapping()` or discovered through `meta.profile` in the IG registry —
         * be produced instead of the base class.
         */
        private readonly FHIRTypeResolverInterface $typeResolver,
        /** Reads `#[FhirProperty]` off whichever concrete classes the resolver returned. */
        private readonly PropertyMetadataProviderInterface $metadataProvider = new PropertyMetadataProvider(),
    ) {
    }

    /**
     * Wire a mapper for one FHIR version, mirroring `FHIRSerializationService::createDefault()`.
     */
    public static function createDefault(FhirVersion $version = FhirVersion::R4): self
    {
        return self::createWithIG(version: $version);
    }

    /**
     * Wire a mapper whose resolver knows about an Implementation Guide's profile classes.
     *
     * @param string $igOutputDirectory Absolute path to the IG output directory; '' to skip scanning
     * @param string $igNamespace       PSR-4 namespace root for that directory; '' to skip
     */
    public static function createWithIG(
        string $igOutputDirectory = '',
        string $igNamespace = '',
        FhirVersion $version = FhirVersion::R4,
    ): self {
        return new self(new FHIRTypeResolver(
            igTypeRegistry: FHIRIGTypeRegistryFactory::create($igOutputDirectory, $igNamespace),
            fhirVersion: $version->value,
        ));
    }

    /**
     * Build a `Parameters` resource from a typed operation payload.
     *
     * @param object $payload An Input or Output instance carrying #[FhirOperationParameter]
     *
     * @return object The resolved `Parameters` class for this mapper's version and registry
     *
     * @throws OperationMappingException when a required parameter is unset or a value cannot be mapped
     */
    public function toParameters(object $payload): object
    {
        $parametersClass = $this->parametersResourceClass();

        return new $parametersClass(parameter: $this->buildParameters($payload));
    }

    /**
     * Rebuild a typed operation payload from a `Parameters` resource.
     *
     * @template T of object
     *
     * @param object          $parameters The version's ParametersResource
     * @param class-string<T> $class      The payload class to construct
     *
     * @return T
     *
     * @throws OperationMappingException when a required parameter is absent from the resource
     */
    public function fromParameters(object $parameters, string $class): object
    {
        /** @var list<object> $entries */
        $entries = $parameters->parameter ?? [];

        return $this->buildPayload($entries, $class);
    }

    /**
     * Read an operation's response body into its typed output, honouring the declared output shape.
     *
     * Only about a quarter of operations answer with a `Parameters` resource. For the majority the
     * response IS the resource, so calling {@see fromParameters()} on it would be wrong — which is
     * why the shape is a generation-time decision carried on the holder rather than something this
     * method guesses from the body it was handed.
     *
     * @param object|null $body           The deserialized response resource; null for an empty body
     * @param string      $operationClass The generated holder carrying #[FhirOperation]
     *
     * @return object|null The typed Output for Parameters-shaped operations, the resource itself for
     *                     the bare-resource shapes, and null for NoOutput
     *
     * @throws OperationMappingException when the body does not match the declared shape
     */
    public function fromResponse(?object $body, string $operationClass): ?object
    {
        $operation = $this->operationOf($operationClass);

        return match ($operation->outputShape) {
            // Modelled explicitly so "succeeded, no body" stays distinguishable from "failed to
            // parse". A body arriving here is a contract violation worth surfacing, not ignoring.
            OperationOutputShape::NoOutput => $body === null
                ? null
                : throw OperationMappingException::unexpectedResponseType('empty body', $body::class, $operation->outputShape->value),
            OperationOutputShape::Parameters => $this->parametersOutput($body, $operation),
            // The spec is literal about this: "If there is only one out parameter, which is a
            // Resource with the parameter name 'return' then the parameter format is not used, and
            // the response is simply the resource itself." No unwrapping step exists.
            OperationOutputShape::BareResource => $this->assertResponseResource(
                $body,
                $operation->outputClass,
                $operation->outputShape,
            ),
            // ...and the un-wrap rule is conditioned on the name `return`. A sole resource-typed OUT
            // parameter under any *other* name does not qualify, so the parameter format IS used and
            // the resource arrives inside a `Parameters` under that name. Collapsing this into the
            // bare case would be wrong in both directions: reading a wrapped body as bare, and
            // emitting a bare body a server would have to guess at.
            OperationOutputShape::NamedBareResource => $this->namedResourceOutput($body, $operation),
        };
    }

    /**
     * Extract the sole resource-typed OUT parameter from the `Parameters` a class-C response wraps it in.
     *
     * @throws OperationMappingException
     */
    private function namedResourceOutput(?object $body, FhirOperation $operation): object
    {
        $wireName = $operation->outputParameterName;

        if ($wireName === null) {
            throw OperationMappingException::unresolvableType($operation->outputShape->value . ' outputParameterName');
        }

        /** @var list<object> $entries */
        $entries = $body->parameter ?? [];

        foreach ($entries as $entry) {
            if ($this->wireName($entry) !== $wireName) {
                continue;
            }

            return $this->assertResponseResource(
                $entry->resource ?? null,
                $operation->outputClass,
                $operation->outputShape,
            );
        }

        throw OperationMappingException::missingNamedOutputParameter($wireName);
    }

    /**
     * @throws OperationMappingException
     */
    private function parametersOutput(?object $body, FhirOperation $operation): object
    {
        $outputClass = $operation->outputClass;

        if ($body === null || $outputClass === null || !class_exists($outputClass)) {
            throw OperationMappingException::unexpectedResponseType($outputClass ?? 'Parameters', get_debug_type($body), $operation->outputShape->value);
        }

        return $this->fromParameters($body, $outputClass);
    }

    /**
     * Build the response body an operation would send for a typed output — the inverse of
     * {@see fromResponse()}.
     *
     * @param string $operationClass The generated holder carrying #[FhirOperation]
     *
     * @throws OperationMappingException when the output does not match the declared shape
     */
    public function toResponse(?object $output, string $operationClass): ?object
    {
        $operation = $this->operationOf($operationClass);

        return match ($operation->outputShape) {
            OperationOutputShape::NoOutput    => null,
            OperationOutputShape::Parameters  => $output === null
                ? null
                : $this->toParameters($output),
            // Class C is wrapped: the un-wrap rule needs the name `return`, which this shape by
            // definition does not have. Emitted as a one-parameter `Parameters` under the declared
            // name, which is exactly what fromResponse() reads back.
            OperationOutputShape::NamedBareResource => $this->wrapNamedResource($output, $operation),
            // The resource is already the body. Nothing to map: this is the whole point of the shape.
            OperationOutputShape::BareResource => $this->assertResponseResource(
                $output,
                $operation->outputClass,
                $operation->outputShape,
            ),
        };
    }

    /**
     * Wrap a class-C output back into the single-parameter `Parameters` its response uses.
     *
     * @throws OperationMappingException
     */
    private function wrapNamedResource(?object $output, FhirOperation $operation): object
    {
        $wireName = $operation->outputParameterName;

        if ($wireName === null) {
            throw OperationMappingException::unresolvableType($operation->outputShape->value . ' outputParameterName');
        }

        $resource = $this->assertResponseResource($output, $operation->outputClass, $operation->outputShape);

        $parametersClass = $this->parametersResourceClass();
        $parameterClass  = $this->parametersParameterClass();

        return new $parametersClass(parameter: [
            new $parameterClass(name: $wireName, resource: $resource),
        ]);
    }

    /**
     * @throws OperationMappingException
     */
    private function assertResponseResource(
        ?object $body,
        ?string $expectedClass,
        OperationOutputShape $shape,
    ): object {
        if ($expectedClass === null) {
            throw OperationMappingException::unresolvableType($shape->value . ' outputClass');
        }

        if (!$body instanceof $expectedClass) {
            throw OperationMappingException::unexpectedResponseType($expectedClass, get_debug_type($body), $shape->value);
        }

        return $body;
    }

    /**
     * @throws OperationMappingException
     */
    private function operationOf(string $operationClass): FhirOperation
    {
        if (!class_exists($operationClass)) {
            throw OperationMappingException::notAnOperationHolder($operationClass);
        }

        $attributes = (new \ReflectionClass($operationClass))->getAttributes(FhirOperation::class);

        if ($attributes === []) {
            throw OperationMappingException::notAnOperationHolder($operationClass);
        }

        return $attributes[0]->newInstance();
    }

    /**
     * @return list<object> ParametersParameter instances
     *
     * @throws OperationMappingException
     */
    private function buildParameters(object $payload): array
    {
        $parameterClass = $this->parametersParameterClass();
        $descriptors    = $this->describe($payload::class);
        $built          = [];

        foreach ($descriptors as $descriptor) {
            $value = $this->readProperty($payload, $descriptor->phpName);

            if ($this->isAbsent($value)) {
                if ($descriptor->isRequired()) {
                    throw OperationMappingException::missingRequiredParameter($descriptor->name, $payload::class);
                }

                continue;
            }

            // A collection emits one Parameters entry per item — repetition on the wire is how FHIR
            // expresses cardinality, not a nested array.
            $items = $descriptor->isCollection() ? (is_array($value) ? array_values($value) : [$value]) : [$value];

            foreach ($items as $item) {
                $built[] = $this->buildOneParameter($descriptor, $item, $parameterClass);
            }
        }

        return $built;
    }

    /**
     * @throws OperationMappingException
     */
    private function buildOneParameter(
        FhirOperationParameter $descriptor,
        mixed $item,
        string $parameterClass,
    ): object {
        // inv-1: exactly one of part, resource, value. The branch order below IS that decision.
        if ($descriptor->partClass !== null) {
            if (!is_object($item)) {
                throw OperationMappingException::unmappableValue($descriptor->name, 'part group', get_debug_type($item));
            }

            return new $parameterClass(
                name: $descriptor->name,
                part: $this->buildParameters($item),
            );
        }

        if ($this->isResourceType($descriptor->type)) {
            if (!is_object($item)) {
                throw OperationMappingException::unmappableValue($descriptor->name, $descriptor->type ?? 'Resource', get_debug_type($item));
            }

            return new $parameterClass(name: $descriptor->name, resource: $item);
        }

        return new $parameterClass(
            name: $descriptor->name,
            value: $this->toValueSlot($descriptor, $item),
        );
    }

    /**
     * Convert a payload value into something the `value[x]` choice can carry.
     *
     * @throws OperationMappingException
     */
    private function toValueSlot(FhirOperationParameter $descriptor, mixed $item): mixed
    {
        // Polymorphic: the caller already chose a type. Passing a bare string here would make the
        // serializer guess between valueCode/valueString/valueDateTime, so refuse instead.
        if ($descriptor->variants !== null && $descriptor->variants !== []) {
            if (is_object($item) || is_bool($item) || is_int($item)) {
                return $item;
            }

            throw OperationMappingException::ambiguousPolymorphicValue($descriptor->name, get_debug_type($item));
        }

        $fhirType = $descriptor->type;

        if ($fhirType === null) {
            return $item;
        }

        // Complex types (Coding, Identifier, …) are already model objects.
        if (is_object($item)) {
            return $item;
        }

        $variant = $this->valueVariantFor($fhirType);

        if ($variant === null) {
            throw OperationMappingException::unmappableValue($descriptor->name, $fhirType, get_debug_type($item));
        }

        // Builtin-backed types (boolean, integer, and decimal — carried as a string to preserve
        // precision) go into the slot as-is; everything else needs its wrapper.
        if (!$variant['wrap']) {
            return $item;
        }

        $wrapper = $variant['phpType'];

        return new $wrapper(value: $item);
    }

    /**
     * @template T of object
     *
     * @param list<object>    $entries
     * @param class-string<T> $class
     *
     * @return T
     *
     * @throws OperationMappingException
     */
    private function buildPayload(array $entries, string $class): object
    {
        $descriptors = $this->describe($class);
        $arguments   = [];

        foreach ($descriptors as $descriptor) {
            // Matching is by wire name only, with no `use` check. That is safe because every
            // descriptor on one payload class shares a direction — an Input holds only `use: in`
            // parameters — so wire names are unique within the class. It would NOT be safe on a
            // combined class: $lookup declares both `property` and `version` in both directions, and
            // the wire `Parameters` carries no `use` marker to tell them apart.
            $matching = array_values(array_filter(
                $entries,
                fn (object $entry): bool => $this->wireName($entry) === $descriptor->name,
            ));

            if ($matching === []) {
                if ($descriptor->isRequired()) {
                    throw OperationMappingException::missingRequiredParameter($descriptor->name, $class);
                }

                continue;
            }

            $values = array_map(fn (object $entry): mixed => $this->readValueSlot($descriptor, $entry), $matching);

            $arguments[$descriptor->phpName] = $descriptor->isCollection() ? $values : $values[0];
        }

        /** @var T */
        return new $class(...$arguments);
    }

    /**
     * @throws OperationMappingException
     */
    private function readValueSlot(FhirOperationParameter $descriptor, object $entry): mixed
    {
        if ($descriptor->partClass !== null) {
            /** @var list<object> $parts */
            $parts = $entry->part ?? [];

            return $this->buildPayload($parts, $descriptor->partClass);
        }

        if (($entry->resource ?? null) !== null) {
            return $entry->resource;
        }

        $value = $entry->value ?? null;

        // Polymorphic parameters keep the wrapper — it is the only record of which variant the wire
        // carried, and it holds any primitive extensions.
        if ($descriptor->variants !== null && $descriptor->variants !== []) {
            return $value;
        }

        return $this->unwrapPrimitive($value);
    }

    /**
     * Unwrap a primitive wrapper back to the bare PHP value the payload class declares.
     *
     * Any primitive extensions on the wrapper are dropped here — see the class docblock. The value
     * is read off `->value` rather than cast via __toString so that a null-valued wrapper carrying
     * only extensions comes back as null rather than an empty string.
     */
    private function unwrapPrimitive(mixed $value): mixed
    {
        if (is_object($value) && property_exists($value, 'value')) {
            /** @var mixed $inner */
            $inner = $value->value;

            return $inner;
        }

        return $value;
    }

    /**
     * Read every #[FhirOperationParameter] off a payload class, in declaration order.
     *
     * Declaration order is the emitted order, and generated classes follow the OperationDefinition's
     * parameter order, so `Parameters.parameter` comes out in definition order.
     *
     * @param class-string $class
     *
     * @return list<FhirOperationParameter>
     *
     * @throws OperationMappingException
     */
    private function describe(string $class): array
    {
        $descriptors = [];

        foreach ((new \ReflectionClass($class))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $descriptors[] = $attribute->newInstance();
            }
        }

        if ($descriptors === []) {
            throw OperationMappingException::notAnOperationPayload($class);
        }

        return $descriptors;
    }

    /**
     * Read a ParametersParameter's wire name, whichever form it is in.
     *
     * `ParametersParameter::$name` is typed `StringPrimitive|string|null`, and which one you get
     * depends on how the resource was built. This mapper's own `toParameters()` sets a bare string;
     * deserializing the same resource from JSON produces a `StringPrimitive`. The second path is the
     * one a real invocation takes — server response → deserialize → `fromParameters()` — so a
     * strict string comparison alone would match nothing on the path that matters most.
     */
    private function wireName(object $entry): ?string
    {
        $name = $entry->name ?? null;

        if ($name === null) {
            return null;
        }

        return is_string($name) ? $name : (string) $name;
    }

    private function readProperty(object $payload, string $phpName): mixed
    {
        $property = new \ReflectionProperty($payload::class, $phpName);

        return $property->isInitialized($payload) ? $property->getValue($payload) : null;
    }

    /**
     * An unset optional parameter is null or an empty collection; `false` and `0` are real values.
     */
    private function isAbsent(mixed $value): bool
    {
        return $value === null || $value === [];
    }

    /**
     * True when the FHIR type names a resource rather than a data type, so it takes the resource slot.
     *
     * Asked of the type resolver rather than answered by probing a hardcoded namespace, so a profile
     * class registered for that resource type wins over the base class.
     */
    private function isResourceType(?string $type): bool
    {
        if ($type === null || $type === '') {
            return false;
        }

        // `Resource` is the abstract base — no concrete class is registered under that name, but a
        // parameter typed with it still takes the resource slot.
        if ($type === 'Resource') {
            return true;
        }

        return $this->typeResolver->resolveResourceType(['resourceType' => $type]) !== null;
    }

    /**
     * The concrete `Parameters` class for this mapper's version and registry.
     *
     * Public because a serializer integration needs it: to read an operation request body it must
     * first denormalize the raw payload into *this* mapper's `Parameters` class, which may be an
     * IG profile rather than the base one. Asking the mapper keeps that single source of truth.
     *
     * @return class-string
     *
     * @throws OperationMappingException
     */
    public function parametersResourceClass(): string
    {
        $fqcn = $this->typeResolver->resolveResourceType(['resourceType' => 'Parameters']);

        if ($fqcn === null || !class_exists($fqcn)) {
            throw OperationMappingException::unresolvableType('Parameters');
        }

        /** @var class-string $fqcn */
        return $fqcn;
    }

    /**
     * The backbone class the resolved `Parameters` declares for its own `parameter` array.
     *
     * Read from that class's `#[FhirProperty]` rather than assembled from a namespace: whichever
     * `Parameters` the resolver returned is the authority on what its parameters are made of, so a
     * profile that narrows the backbone is honoured for free.
     *
     * @return class-string
     *
     * @throws OperationMappingException
     */
    private function parametersParameterClass(): string
    {
        $metadata = $this->metadataProvider->getPropertyMetadata($this->parametersResourceClass());
        $fqcn     = $metadata['parameter']->phpItemClass ?? null;

        if ($fqcn === null || !class_exists($fqcn)) {
            throw OperationMappingException::unresolvableType('Parameters.parameter');
        }

        /** @var class-string $fqcn */
        return $fqcn;
    }

    /**
     * Resolve a FHIR type code to the PHP type the `value[x]` slot expects, or null when it is a
     * PHP builtin that needs no wrapping.
     *
     * `ParametersParameter.value[x]` is the universal FHIR datatype union, so its own choice variants
     * are the authoritative fhirType → phpType map — including which types are builtins. Deriving
     * the answer from that beats a hand-maintained table: it cannot drift from the models, and it
     * follows the resolved (possibly profiled) class rather than a guessed namespace.
     *
     * @return array{wrap: bool, phpType: string}|null null when the type is not a known value variant
     */
    private function valueVariantFor(string $fhirType): ?array
    {
        $metadata = $this->metadataProvider->getPropertyMetadata($this->parametersParameterClass());
        $variants = $metadata['value']->variants ?? null;

        if ($variants === null) {
            return null;
        }

        foreach ($variants as $variant) {
            if ($variant->fhirType === $fhirType) {
                return ['wrap' => !$variant->isBuiltin, 'phpType' => $variant->phpType];
            }
        }

        return null;
    }
}
