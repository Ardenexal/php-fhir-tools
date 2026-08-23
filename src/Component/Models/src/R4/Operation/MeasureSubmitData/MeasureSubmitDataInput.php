<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\MeasureSubmitData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReportResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-submit-data',
    use: 'in',
    version: 'R4',
    operation: 'MeasureSubmitData',
    path: '',
)]
final class MeasureSubmitDataInput
{
    /**
     * @param list<AbstractResource> $resource
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'measureReport',
            phpName: 'measureReport',
            use: 'in',
            min: 1,
            max: '1',
            type: 'MeasureReport',
            documentation: 'The measure report being submitted',
        )]
        public readonly ?MeasureReportResource $measureReport = null,
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Resource',
            documentation: 'The individual resources that make up the data-of-interest being submitted',
        )]
        public readonly array $resource = [],
    ) {
    }
}
