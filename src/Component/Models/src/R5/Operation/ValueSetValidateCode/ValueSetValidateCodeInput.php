<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ValueSetValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSetResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ValueSet-validate-code',
    use: 'in',
    version: 'R5',
    operation: 'ValueSetValidateCode',
    path: '',
)]
final class ValueSetValidateCodeInput
{
    /**
     * @param list<string> $useSupplement
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Value set Canonical URL. The server must know the value set (e.g. it is defined explicitly in the server\'s value sets, or it is defined implicitly by some code system known to the server',
            scope: ['type'],
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'context',
            phpName: 'context',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The context of the value set, so that the server can resolve this to a value set to validate against. The recommended format for this URI is [Structure Definition URL]#[name or path into structure definition] e.g. http://hl7.org/fhir/StructureDefinition/observation-hspc-height-hspcheight#Observation.interpretation. Other forms may be used but are not defined. This form is only usable if the terminology server also has access to the conformance registry that the server is using, but can be used to delegate the mapping from an application context to a binding at run-time',
        )]
        public readonly ?string $context = null,
        #[FhirOperationParameter(
            name: 'valueSet',
            phpName: 'valueSet',
            use: 'in',
            min: 0,
            max: '1',
            type: 'ValueSet',
            documentation: 'The value set is provided directly as part of the request. Servers may choose not to accept value sets in this fashion. This parameter is used when the client wants the server to expand a value set that is not stored on the server',
            scope: ['type'],
        )]
        public readonly ?ValueSetResource $valueSet = null,
        #[FhirOperationParameter(
            name: 'valueSetVersion',
            phpName: 'valueSetVersion',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The identifier that is used to identify a specific version of the value set to be used when validating the code. This is an arbitrary value managed by the value set author and is not expected to be globally unique. For example, it might be a timestamp (e.g. yyyymmdd) if a managed version is not available.',
            scope: ['type'],
        )]
        public readonly ?string $valueSetVersion = null,
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be validated. If a code is provided, a system or a context must be provided (if a context is provided, then the server SHALL ensure that the code is not ambiguous without a system)',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code that is to be validated',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'systemVersion',
            phpName: 'systemVersion',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system, if one was provided in the source data',
        )]
        public readonly ?string $systemVersion = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The display associated with the code, if provided. If a display is provided a code must be provided. If no display is provided, the server cannot validate the display value, but may choose to return a recommended display name using the display parameter in the outcome. Whether displays are case sensitive is code system dependent',
        )]
        public readonly ?string $display = null,
        #[FhirOperationParameter(name: 'coding', phpName: 'coding', use: 'in', min: 0, max: '1', type: 'Coding', documentation: 'A coding to validate')]
        public readonly ?Coding $coding = null,
        #[FhirOperationParameter(
            name: 'codeableConcept',
            phpName: 'codeableConcept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'A full codeableConcept to validate. The server returns true if one of the coding values is in the value set, and may also validate that the codings are not in conflict with each other if more than one is present',
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
            documentation: "If this parameter has a value of true or the parametter is ommitted, the client is stating that the validation is being performed in a context where a concept designated as 'abstract' is appropriate/allowed to be used, and the server should regard abstract codes as valid. If this parameter is false, abstract codes are not considered to be valid.\n\nNote that. 'abstract' is a property defined by many HL7 code systems that indicates that the concept is a logical grouping concept that is not intended to be used as a 'concrete' concept to in an actual patient/care/process record. This language is borrowed from object-orientated theory where 'abstract' entities are never instantiated. However in the general record and terminology eco-system, there are many contexts where it is appropriate to use these codes e.g. as decision making criterion, or when editing value sets themselves. This parameter allows a client to indicate to the server that it is working in such a context.",
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
        #[FhirOperationParameter(
            name: 'useSupplement',
            phpName: 'useSupplement',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'The supplement must be used when validating the code. Use of this parameter should result in $validate-code behaving the same way as if the supplements were included in the value set definition using the [http://hl7.org/fhir/StructureDefinition/valueset-supplement](http://hl7.org/fhir/extensions/StructureDefinition-valueset-supplement.html)',
        )]
        public readonly array $useSupplement = [],
    ) {
    }
}
