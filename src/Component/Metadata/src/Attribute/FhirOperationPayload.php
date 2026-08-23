<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Marks a generated operation IN/OUT payload class, or one of its nested `part[]` groups.
 *
 * Payload classes deliberately carry **no** `#[FhirResource]` or `#[FhirProperty]`: those are what
 * `FHIRResourceJsonNormalizer` dispatches on, and if it claimed one of these objects it would walk
 * the properties and emit flat keys (`{"code":"...","system":"..."}`) instead of a parameter array.
 * Payloads reach the wire only via `OperationParameterMapper`, which converts them to a real
 * `Parameters` resource first.
 *
 * That absence creates a second problem this attribute solves: the generator pipeline routes classes
 * to output namespaces by inspecting their class attributes, and a class carrying none would fall
 * through to the `DataType` default. This marker is the routing signal — and it is metadata only,
 * so it does not put payloads on any normalizer's `supports*()` hot path.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class FhirOperationPayload
{
    public function __construct(
        /** Canonical URL of the OperationDefinition this payload was generated from. */
        public readonly string $operationUrl,
        /** Which direction this payload carries: 'in' or 'out'. */
        public readonly string $use,
        /**
         * FHIR version this payload targets: 'R4', 'R4B' or 'R5'.
         *
         * Carried so the class is self-describing. A serializer handed one of these has only the
         * class to go on, and the mapper it must delegate to is version-scoped at construction —
         * deriving the version by parsing the namespace would be the guessing that N18 warns about.
         */
        public readonly string $version,
        /**
         * Class stem of the owning operation, e.g. 'CodeSystemLookup'.
         *
         * Used to nest generated files under their operation's directory, the way backbone elements
         * nest under their parent resource.
         */
        public readonly string $operation,
        /**
         * Dot-separated parameter path for a nested `part[]` group, or '' for a top-level payload.
         *
         * Keyed by path rather than by name because `$lookup` collides on `property` in both
         * directions and on `property.value` vs `property.subproperty.value`.
         */
        public readonly string $path = '',
    ) {
    }
}
