<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'in',
    version: 'R4B',
    operation: 'CodeSystemLookup',
    path: '',
)]
final class CodeSystemLookupInput
{
    /**
     * @param list<string> $property
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be located. If a code is provided, a system must be provided',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code that is to be located',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system, if one was provided in the source data',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(name: 'coding', phpName: 'coding', use: 'in', min: 0, max: '1', type: 'Coding', documentation: 'A coding to look up')]
        public readonly ?Coding $coding = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date for which the information should be returned. Normally, this is the current conditions (which is the default value) but under some circumstances, systems need to acccess this information as it would have been in the past. A typical example of this would be where code selection is constrained to the set of codes that were available when the patient was treated, not when the record is being edited. Note that which date is appropriate is a matter for implementation policy.',
        )]
        public readonly ?string $date = null,
        #[FhirOperationParameter(
            name: 'displayLanguage',
            phpName: 'displayLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The requested language for display (see $expand.displayLanguage)',
        )]
        public readonly ?string $displayLanguage = null,
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'A property that the client wishes to be returned in the output. If no properties are specified, the server chooses what to return. The following properties are defined for all code systems: url, name, version (code system info) and code information: display, definition, designation, parent and child, and for designations, lang.X where X is a designation language code. Some of the properties are returned explicit in named parameters (when the names match), and the rest (except for lang.X) in the property parameter group',
        )]
        public readonly array $property = [],
    ) {
    }
}
