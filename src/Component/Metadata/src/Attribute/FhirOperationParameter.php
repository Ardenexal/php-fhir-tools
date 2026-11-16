<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Attribute encoding one `OperationDefinition.parameter` entry on a generated operation IN/OUT class.
 *
 * Generated operation classes deliberately do NOT carry #[FhirResource] / #[FhirProperty]. Those
 * attributes make the resource normalizers walk a class's properties and emit them as flat JSON
 * keys, which is wrong here: the wire format of an operation input is a `Parameters` resource, so
 * `{"code": "..."}` must become `{"parameter":[{"name":"code","valueCode":"..."}]}`. This attribute
 * keeps operation classes off the serializer's property-walking path, and a dedicated mapper reads
 * it to build a `Parameters` resource which the normal serialization service then renders.
 *
 * Field semantics follow `OperationDefinition.parameter` (verified against the R4 and R5
 * StructureDefinitions, 2026-08-07). Two are easy to get wrong:
 *
 *  - `max` is a **string**, not an int. The spec types it `1..1 string` and `"*"` is a legal value
 *    meaning unbounded — it is what drives PHP array typing. See {@see self::isCollection()}.
 *  - `type` is `0..1` and genuinely absent on a parameter that is a pure `part` group (a backbone
 *    of nested parameters carries no type of its own).
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FhirOperationParameter
{
    /**
     * @param list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string}>|null $variants
     *                                                                                                              Concrete types for a polymorphic parameter. Same shape as FhirProperty::$variants
     *                                                                                                              and PropertyVariantMetadata, deliberately — the choice machinery is shared.
     * @param list<string>                                                                               $scope
     *                                                                                                              R5-only `parameter.scope` codes ('instance', 'type', 'system').
     * @param class-string|null                                                                          $partClass
     *                                                                                                              FQCN of the nested payload class; the mapper instantiates it directly
     */
    public function __construct(
        /**
         * The wire name, verbatim as published. Never normalised, never "corrected".
         *
         * Core parameter names include leading underscores (`_count`, `_since`), PHP reserved words
         * (`return`, `use`, `default`), hyphens (`check-system-version`), and — in R5 — dots
         * (`targetIdentifier.period`). R5 also ships `targetIdentifer.preferred`, a typo present in
         * the published specification. Round-tripping requires emitting exactly what was published,
         * typo included, so this value must survive generation untouched.
         */
        public readonly string $name,
        /**
         * The PHP property/parameter identifier carrying this parameter.
         *
         * Stored explicitly rather than derived. The transformation from wire name to a legal PHP
         * identifier is lossy and not invertible: `_count` and `count` can both appear, `use` must
         * be escaped because it is a reserved word, and `check-system-version` loses its hyphens.
         * Nothing may reconstruct one of these from the other in either direction.
         */
        public readonly string $phpName,
        /** 'in' or 'out' — `OperationDefinition.parameter.use`, required by the spec (1..1). */
        public readonly string $use,
        /** Minimum cardinality. `min >= 1` means the parameter is required; see self::isRequired(). */
        public readonly int $min,
        /**
         * Maximum cardinality as a string: '0', '1', '*', or any positive integer.
         * Compared as a string on purpose — '*' is legal and int-casting it silently yields 0.
         */
        public readonly string $max,
        /**
         * FHIR type code ('code', 'Coding', 'Element'), or null for a pure `part` group.
         * Null does not mean "untyped" — it means this parameter's shape lives in $partClass.
         */
        public readonly ?string $type = null,
        /** Populated for polymorphic parameters (type 'Element' or '*'); null otherwise. */
        public readonly ?array $variants = null,
        /**
         * FQCN of the generated class holding this parameter's `part[]` children.
         *
         * Path-keyed, because parameter names collide: $lookup declares `property` twice at the top
         * level (once `use: in` typed `code`, once `use: out` as a backbone group) and `property.value`
         * collides with `property.subproperty.value`. Without this the reflection-driven mapper
         * cannot reach the nested types.
         */
        public readonly ?string $partClass = null,
        /** R5-only `parameter.scope` (0..*). Captured as metadata; not enforced (see backlog). */
        public readonly array $scope = [],
        /** `parameter.searchType` (0..1) — set only when type is 'string' and the parameter is a search param. */
        public readonly ?string $searchType = null,
        /** `parameter.documentation` (0..1). Carried so generated classes can render a docblock. */
        public readonly ?string $documentation = null,
    ) {
    }

    /**
     * True when this parameter holds a list and must be typed as a PHP array.
     *
     * Unbounded ('*') and any explicit bound above 1 both mean a collection. Note the string
     * comparison on '*' happens first: `(int) '*'` is 0, so an int-first implementation would
     * classify the unbounded case as scalar — the exact inversion of the truth.
     */
    public function isCollection(): bool
    {
        return $this->max === '*' || (int) $this->max > 1;
    }

    /**
     * True when the spec requires this parameter to be present.
     *
     * Generated model classes make every property nullable regardless of `min`, so a
     * cardinality-invalid object constructs, passes static analysis and serializes. The mapper
     * uses this to reject that case instead of emitting invalid FHIR.
     */
    public function isRequired(): bool
    {
        return $this->min >= 1;
    }
}
