<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * A **synthetic** operation IN class, not a faithful model of any single operation.
 *
 * Every wire name here is real, but they are assembled from across the core packages —
 * `code`/`property` from CodeSystem/$lookup, `use` from ValueSet/$validate-code, `_count` from
 * $everything, `check-system-version` from ValueSet/$expand, and `targetIdentifer.preferred` from
 * R5 ConceptMap/$translate (whose missing 'i' is a typo in the published specification, not here).
 * The point is to exercise the wire-name/PHP-identifier split against the awkward cases in one
 * class. Do NOT read this as a reference for what any operation's input looks like — for that, see
 * {@see LookupOutputFixture}, which does mirror a real definition.
 *
 * A named file-level class, not an anonymous one: attributes on anonymous classes are instantiated
 * in a context where `self::` does not resolve, which fails fatally at getAttributes() time.
 */
final class AwkwardNamesInputFixture
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be located.',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
        )]
        public readonly array $property = [],
        #[FhirOperationParameter(
            name: 'use',
            phpName: 'useParameter',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
        )]
        public readonly ?string $useParameter = null,
        #[FhirOperationParameter(
            name: '_count',
            phpName: 'count',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
        )]
        public readonly ?int $count = null,
        #[FhirOperationParameter(
            name: 'check-system-version',
            phpName: 'checkSystemVersion',
            use: 'in',
            min: 0,
            max: '2',
            type: 'canonical',
        )]
        public readonly array $checkSystemVersion = [],
        #[FhirOperationParameter(
            name: 'targetIdentifer.preferred',
            phpName: 'targetIdentiferPreferred',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            scope: ['instance', 'type'],
        )]
        public readonly ?bool $targetIdentiferPreferred = null,
        /**
         * searchType is constrained by two published invariants, both honoured here:
         * opd-2 `searchType.exists() implies type = 'string'`, and
         * opd-4 `(use = 'out') implies searchType.empty()`.
         */
        #[FhirOperationParameter(
            name: 'filter',
            phpName: 'filter',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'token',
        )]
        public readonly ?string $filter = null,
    ) {
    }
}
