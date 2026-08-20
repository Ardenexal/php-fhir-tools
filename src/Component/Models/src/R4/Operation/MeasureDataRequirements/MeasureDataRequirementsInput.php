<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\MeasureDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-data-requirements',
    use: 'in',
    version: 'R4',
    operation: 'MeasureDataRequirements',
    path: '',
)]
final class MeasureDataRequirementsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'periodStart',
            phpName: 'periodStart',
            use: 'in',
            min: 1,
            max: '1',
            type: 'date',
            documentation: 'The start of the measurement period. In keeping with the semantics of the date parameter used in the FHIR search operation, the period will start at the beginning of the period implied by the supplied timestamp. E.g. a value of 2014 would set the period start to be 2014-01-01T00:00:00 inclusive',
        )]
        public readonly ?string $periodStart = null,
        #[FhirOperationParameter(
            name: 'periodEnd',
            phpName: 'periodEnd',
            use: 'in',
            min: 1,
            max: '1',
            type: 'date',
            documentation: 'The end of the measurement period. The period will end at the end of the period implied by the supplied timestamp. E.g. a value of 2014 would set the period end to be 2014-12-31T23:59:59 inclusive',
        )]
        public readonly ?string $periodEnd = null,
    ) {
    }
}
