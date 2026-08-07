<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ObservationLastn;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Observation-lastn',
    use: 'in',
    version: 'R5',
    operation: 'ObservationLastn',
    path: '',
)]
final class ObservationLastnInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'max',
            phpName: 'max',
            use: 'in',
            min: 0,
            max: '1',
            type: 'positiveInt',
            documentation: '`max` is  an optional input parameter to the *lastn* query operation.  It is used to specify the maximum number of Observations to return from each group. For example for the query "Fetch the last 3 results for all vitals for a patient" `max` = 3.',
        )]
        public readonly ?int $max = null,
    ) {
    }
}
