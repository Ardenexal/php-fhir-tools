<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystemResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-validate-code',
    use: 'in',
    version: 'R4',
    operation: 'CodeSystemValidateCode',
    path: '',
)]
final class CodeSystemValidateCodeInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'CodeSystem URL. The server must know the code system (e.g. it is defined explicitly in the server\'scode systems, or it is known implicitly by the server',
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'codeSystem',
            phpName: 'codeSystem',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeSystem',
            documentation: 'The codeSystem is provided directly as part of the request. Servers may choose not to accept code systems in this fashion. This parameter is used when the client wants the server to check against a code system that is not stored on the server',
        )]
        public readonly ?CodeSystemResource $codeSystem = null,
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be validated',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the code system, if one was provided in the source data',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The display associated with the code, if provided. If a display is provided a code must be provided. If no display is provided, the server cannot validate the display value, but may choose to return a recommended display name in an extension in the outcome. Whether displays are case sensitive is code system dependent',
        )]
        public readonly ?string $display = null,
        #[FhirOperationParameter(
            name: 'coding',
            phpName: 'coding',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'A coding to validate. The system must match the specified code system',
        )]
        public readonly ?Coding $coding = null,
        #[FhirOperationParameter(
            name: 'codeableConcept',
            phpName: 'codeableConcept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'A full codeableConcept to validate. The server returns true if one of the coding values is in the code system, and may also validate that the codings are not in conflict with each other if more than one is present',
        )]
        public readonly ?CodeableConcept $codeableConcept = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date for which the validation should be checked. Normally, this is the current conditions (which is the default values) but under some circumstances, systems need to validate that a correct code was used at some point in the past. A typical example of this would be where code selection is constrained to the set of codes that were available when the patient was treated, not when the record is being edited. Note that which date is appropriate is a matter for implementation policy.',
        )]
        public readonly ?string $date = null,
        #[FhirOperationParameter(
            name: 'abstract',
            phpName: 'abstract',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: "If this parameter has a value of true, the client is stating that the validation is being performed in a context where a concept designated as 'abstract' is appropriate/allowed to be used, and the server should regard abstract codes as valid. If this parameter is false, abstract codes are not considered to be valid.\n\nNote that. 'abstract' is a property defined by many HL7 code systems that indicates that the concept is a logical grouping concept that is not intended to be used asa 'concrete' concept to in an actual patient/care/process record. This language is borrowed from Object Orienated theory where 'asbtract' objects are never instantiated. However in the general record and terminology eco-system, there are many contexts where it is appropraite to use these codes e.g. as decision making criterion, or when editing value sets themselves. This parameter allows a client to indicate to the server that it is working in such a context.",
        )]
        public readonly ?bool $abstract = null,
        #[FhirOperationParameter(
            name: 'displayLanguage',
            phpName: 'displayLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'Specifies the language to be used for description when validating the display property',
        )]
        public readonly ?string $displayLanguage = null,
    ) {
    }
}
