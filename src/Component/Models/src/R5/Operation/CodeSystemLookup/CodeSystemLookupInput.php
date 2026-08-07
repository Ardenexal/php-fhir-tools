<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'in',
    version: 'R5',
    operation: 'CodeSystemLookup',
    path: '',
)]
final class CodeSystemLookupInput
{
    /**
     * @param list<string> $property
     * @param list<string> $useSupplement
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
            scope: ['type'],
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
            scope: ['type'],
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
            documentation: 'The requested language for display (see CodeSystem.concept.designation.language)',
        )]
        public readonly ?string $displayLanguage = null,
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'A property that the client wishes to be returned in the output. If no properties are specified, the server chooses what to return. The following properties are defined for all code systems: name, version (code system info) and code information: display, designation, and lang.X where X is a designation language code. These properties are returned explicitly in named out parameters with matching names, or in designations. In addition, any property codes defined by [this specification](codesystem.html#defined-props) or by the CodeSystem ([CodeSystem.property.code](codesystem-definitions.html#CodeSystem.property)) are allowed, and these are returned in the out parameter ```property```',
        )]
        public readonly array $property = [],
        #[FhirOperationParameter(
            name: 'useSupplement',
            phpName: 'useSupplement',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'Supplements to take into account when performing the $lookup operation. The supplements must be for the same CodeSystem. By default, supplements for the code system are not automatically included except where they provide additional designations that may be indicated by, for example, the displayLanguage parameter',
        )]
        public readonly array $useSupplement = [],
    ) {
    }
}
