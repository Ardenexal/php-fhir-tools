<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Attribute describing a FHIR operation on its generated holder class.
 *
 * The holder carries invocation metadata and the pointers to the typed IN/OUT classes; the classes
 * themselves carry `#[FhirOperationParameter]` per parameter. The split exists because `$lookup`
 * declares `version` as both an IN and an OUT parameter and `property` as both an IN `code` and an
 * OUT backbone group — a single flat class cannot represent either.
 *
 * The three invocation levels are not decoration: they differ between versions for the same
 * operation. R4 `CodeSystem/$lookup` is type-level only (`instance: false`), while R5 adds
 * instance-level invocation. Reading them off the definition rather than assuming is what keeps one
 * mapper serving every version.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class FhirOperation
{
    /**
     * @param list<string> $resource Resource types this operation is defined for ('CodeSystem').
     *                               Empty for a system-level operation bound to no type.
     */
    public function __construct(
        /** The operation code as invoked, without the `$` (e.g. 'lookup', 'validate-code'). */
        public readonly string $code,
        /** Canonical URL of the OperationDefinition this was generated from. */
        public readonly string $url,
        /** FHIR version this holder targets: 'R4', 'R4B' or 'R5'. */
        public readonly string $version,
        /**
         * FQCN of the typed IN class, or `''` when the operation declares no `use: in` parameters.
         *
         * The empty string is a deliberate sentinel, not a generation failure — 12 shipped holders
         * carry it (`ResourceMeta`, `CapabilityStatementVersions`, `ActivityDefinitionDataRequirements`
         * and `PlanDefinitionDataRequirements`, across R4/R4B/R5). Consumers must therefore check for
         * `''` and not only for a missing class: `new ($op->inputClass)()` throws
         * `Error: Class "" not found`, and `class_exists('')` is false.
         *
         * It is `string` rather than `?string` (unlike `$outputClass`, which is genuinely null for
         * `NoOutput`) because changing it would mean regenerating every holder. The contract was
         * previously recorded only in
         * `tests/Integration/GeneratedOperationClassCountMatchesParameterPathsTest.php`
         * (search: "an operation with no IN parameters carries") — which is a monorepo test a library
         * consumer never reads. Stated here because this attribute *is* the public surface.
         */
        public readonly string $inputClass,
        /** How the response is shaped — see the distribution table on the enum. */
        public readonly OperationOutputShape $outputShape,
        /**
         * FQCN of the typed OUT class, or of the resource itself for the bare-resource shapes.
         * Null when outputShape is NoOutput.
         */
        public readonly ?string $outputClass = null,
        public readonly array $resource = [],
        /** True when invocable as `[base]/[Type]/[id]/$code`. */
        public readonly bool $instance = false,
        /** True when invocable as `[base]/[Type]/$code`. */
        public readonly bool $type = false,
        /** True when invocable as `[base]/$code`. */
        public readonly bool $system = false,
        /**
         * For the NamedBareResource shape, the OUT parameter's name. Null otherwise —
         * BareResource is `return` by definition, and the other shapes do not have a single name.
         */
        public readonly ?string $outputParameterName = null,
    ) {
    }
}
