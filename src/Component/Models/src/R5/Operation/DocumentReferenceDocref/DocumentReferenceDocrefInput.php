<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\DocumentReferenceDocref;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/DocumentReference-docref',
    use: 'in',
    version: 'R5',
    operation: 'DocumentReferenceDocref',
    path: '',
)]
final class DocumentReferenceDocrefInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'patient',
            phpName: 'patient',
            use: 'in',
            min: 1,
            max: '1',
            type: 'id',
            documentation: 'The id of the patient resource located on the server on which this operation is executed.  If there is no match, an empty Bundle is returned',
        )]
        public readonly ?string $patient = null,
        #[FhirOperationParameter(
            name: 'start',
            phpName: 'start',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date range relates to care dates, not record currency dates - e.g. all records relating to care provided in a certain date range. If no start date is provided, all documents prior to the end date are in scope.  If neither a start date nor an end date is provided, the most recent or current document is in scope.  The client **SHOULD** provide values precise to the second + time offset.',
        )]
        public readonly ?string $start = null,
        #[FhirOperationParameter(
            name: 'end',
            phpName: 'end',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date range relates to care dates, not record currency dates - e.g. all records relating to care provided in a certain date range. If no end date is provided, all documents subsequent to the start date are in scope. If neither a start date nor an end date is provided, the most recent or current document is in scope.  The client **SHOULD** provide values precise to the second + time offset.',
        )]
        public readonly ?string $end = null,
        #[FhirOperationParameter(
            name: 'type',
            phpName: 'type',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'The type relates to document type e.g. for the LOINC code for a C-CDA Clinical Summary of Care (CCD) is 34133-9 (Summary of episode note). If no type is provided, the CCD document, if available, SHALL be in scope and all other document types MAY be in scope',
        )]
        public readonly ?CodeableConcept $type = null,
        #[FhirOperationParameter(
            name: 'on-demand',
            phpName: 'onDemand',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'This on-demand parameter allows client to dictate whether they are requesting only \'on-demand\' or both \'on-demand\' and \'stable\' documents (or delayed/deferred assembly) that meet the query parameters',
        )]
        public readonly ?bool $onDemand = null,
    ) {
    }
}
