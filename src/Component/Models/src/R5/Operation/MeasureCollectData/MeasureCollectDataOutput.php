<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureCollectData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MeasureReportResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-collect-data',
    use: 'out',
    version: 'R5',
    operation: 'MeasureCollectData',
    path: '',
)]
final class MeasureCollectDataOutput
{
    /**
     * @param list<AbstractResource> $resource
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'measureReport',
            phpName: 'measureReport',
            use: 'out',
            min: 1,
            max: '1',
            type: 'MeasureReport',
            documentation: 'A MeasureReport of type data-exchange detailing the results of the operation',
        )]
        public readonly ?MeasureReportResource $measureReport = null,
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'out',
            min: 0,
            max: '*',
            type: 'Resource',
            documentation: 'The result resources that make up the data-of-interest for the measure',
        )]
        public readonly array $resource = [],
    ) {
    }
}
