<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;

/**
 * Hand-written stand-in for the generated R4 `CodeSystem/$lookup` IN class.
 *
 * Written by hand so the mapper is proven against a target that exists before the generator does.
 * Every parameter, cardinality and type here is transcribed from
 * `hl7.fhir.r4.core@4.0.1` `OperationDefinition-CodeSystem-lookup.json` — see the committed copy at
 * `src/Component/Serialization/tests/Fixtures/OperationDefinitions/r4-CodeSystem-lookup.json`.
 *
 * Differences from {@see \Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5\CodeSystemLookupInput}:
 * R5 adds `useSupplement` (0..* canonical). Everything else is identical, which is the point — the
 * mapper must not branch on version to handle that.
 *
 * Note every property is nullable or defaulted even where the definition says `min: 1`, matching how
 * generated models behave. Cardinality is enforced by the mapper reading `min`, not by the
 * constructor signature.
 */
final class CodeSystemLookupInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be located. If a code is provided, a system must be provided.',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code that is to be located.',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system, if one was provided in the source data.',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'coding',
            phpName: 'coding',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'A coding to look up.',
        )]
        public readonly ?Coding $coding = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date for which the information should be returned.',
        )]
        public readonly ?string $date = null,
        #[FhirOperationParameter(
            name: 'displayLanguage',
            phpName: 'displayLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The requested language for display.',
        )]
        public readonly ?string $displayLanguage = null,
        /**
         * `max: '*'` — a list, and the IN `property` that collides by name with the OUT `property`
         * backbone group on {@see CodeSystemLookupOutput}. Same wire name, different `use`,
         * different shape.
         *
         * @var list<string>
         */
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'A property that the client wishes to be returned in the output.',
        )]
        public readonly array $property = [],
    ) {
    }
}
