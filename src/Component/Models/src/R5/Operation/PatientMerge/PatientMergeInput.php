<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientMerge;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Patient-merge',
    use: 'in',
    version: 'R5',
    operation: 'PatientMerge',
    path: '',
)]
final class PatientMergeInput
{
    /**
     * @param list<Identifier> $sourcePatientIdentifier
     * @param list<Identifier> $targetPatientIdentifier
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'source-patient',
            phpName: 'sourcePatient',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Reference',
            documentation: 'A direct resource reference to the **source** patient resource (this may include an identifier).',
        )]
        public readonly ?Reference $sourcePatient = null,
        #[FhirOperationParameter(
            name: 'source-patient-identifier',
            phpName: 'sourcePatientIdentifier',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Identifier',
            documentation: 'When source-patient-identifiers are provided, the server is expected to perform an internal lookup to identify the source patient record. The server SHALL reject the request if the provided identifiers do not resolve to a single patient record. This resolution MAY occur asynchronously, for example, as part of a review by a user.',
        )]
        public readonly array $sourcePatientIdentifier = [],
        #[FhirOperationParameter(
            name: 'target-patient',
            phpName: 'targetPatient',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Reference',
            documentation: "A direct resource reference to the **target** patient resource.\r\rThis is the surviving patient resource, the target for the merge.",
        )]
        public readonly ?Reference $targetPatient = null,
        #[FhirOperationParameter(
            name: 'target-patient-identifier',
            phpName: 'targetPatientIdentifier',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Identifier',
            documentation: 'When target-patient-identifiers are provided, the server is expected to perform an internal lookup to identify the target patient record. The server SHALL reject the request if the provided identifiers do not resolve to a single patient record. This resolution MAY occur asynchronously, for example, as part of a review by a user.',
        )]
        public readonly array $targetPatientIdentifier = [],
        #[FhirOperationParameter(
            name: 'result-patient',
            phpName: 'resultPatient',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Patient',
            documentation: "The details of the Patient resource that is expected to be updated to complete with and must have the same patient.id and provided identifiers included.\r\rThis resource MUST have the link property included referencing the source patient resource.\r\rIt will be used to perform an update on the target patient resource.\r\rIn the absence of this parameter the servers should copy all identifiers from the source patient into the target patient, and include the link property (as shown in the example below).\r\rThis is often used when properties from the source patient are desired to be included in the target resource.\r\rThe receiving system may also apply other internal business rules onto the merge which may make the resource different from what is provided here.",
        )]
        public readonly ?PatientResource $resultPatient = null,
        #[FhirOperationParameter(
            name: 'preview',
            phpName: 'preview',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: "If this is set to true then the merge will not be actually performed; an OperationOutcome will be returned in the Parameters response that will indicate that no merge has occurred and may include other diagnostic info if desired, such as the scale of the merge.\r\re.g. Issue.details.text \"Preview only Patient merge - no issues detected\"\r\re.g. Issue.diagnostics \"Merge would update: 10 years of content or 120 resources\"\r\rThe resulting target patient resource will also be returned in the result.",
        )]
        public readonly ?bool $preview = null,
    ) {
    }
}
