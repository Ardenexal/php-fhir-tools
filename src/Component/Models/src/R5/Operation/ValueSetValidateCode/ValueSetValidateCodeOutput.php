<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ValueSetValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ValueSet-validate-code',
    use: 'out',
    version: 'R5',
    operation: 'ValueSetValidateCode',
    path: '',
)]
final class ValueSetValidateCodeOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'result',
            phpName: 'result',
            use: 'out',
            min: 1,
            max: '1',
            type: 'boolean',
            documentation: 'True if the concept details supplied are valid',
        )]
        public readonly ?bool $result = null,
        #[FhirOperationParameter(
            name: 'message',
            phpName: 'message',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Error details, if result = false. If this is provided when result = true, the message carries hints and warnings',
        )]
        public readonly ?string $message = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'A valid display for the concept if the system wishes to display this to a user',
        )]
        public readonly ?string $display = null,
        #[FhirOperationParameter(name: 'code', phpName: 'code', use: 'out', min: 0, max: '1', type: 'code', documentation: 'The code that was validated')]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'out',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code that was validated',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system of the code that was validated',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'codeableConcept',
            phpName: 'codeableConcept',
            use: 'out',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'A codeableConcept containing codings for all the validated codes',
        )]
        public readonly ?CodeableConcept $codeableConcept = null,
        #[FhirOperationParameter(
            name: 'issues',
            phpName: 'issues',
            use: 'out',
            min: 0,
            max: '1',
            type: 'OperationOutcome',
            documentation: 'List of itemised issues with paths constrained to simple FHIRPath. Examples are CodeableConcept, CodeableConcept.coding[0], CodeableConcept.coding[1].display, or Coding.display',
        )]
        public readonly ?OperationOutcomeResource $issues = null,
    ) {
    }
}
