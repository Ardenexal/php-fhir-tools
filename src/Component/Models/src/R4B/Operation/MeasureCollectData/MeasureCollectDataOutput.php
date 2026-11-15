<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\MeasureCollectData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MeasureReportResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-collect-data',
    use: 'out',
    version: 'R4B',
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
            documentation: 'A MeasureReport of type data-collection detailing the results of the operation',
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
