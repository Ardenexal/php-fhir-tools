<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureCollectData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-collect-data',
    use: 'in',
    version: 'R5',
    operation: 'MeasureCollectData',
    path: '',
)]
final class MeasureCollectDataInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'periodStart',
            phpName: 'periodStart',
            use: 'in',
            min: 1,
            max: '1',
            type: 'date',
            documentation: 'The start of the measurement period. In keeping with the semantics of the date parameter used in the FHIR search operation, the period will start at the beginning of the period implied by the supplied timestamp. E.g. a value of 2014 would set the period s',
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
        #[FhirOperationParameter(
            name: 'measure',
            phpName: 'measure',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The measure to evaluate. This parameter is only required when the operation is invoked on the resource type, it is not used when invoking the operation on a Measure instance',
            scope: ['type'],
        )]
        public readonly ?string $measure = null,
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'Subject for which the measure will be collected. If not specified, measure data will be collected for all subjects that meet the requirements of the measure. If specified, the measure will only be calculated for the referenced subject(s)',
        )]
        public readonly ?string $subject = null,
        #[FhirOperationParameter(
            name: 'practitioner',
            phpName: 'practitioner',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'Practitioner for which the measure will be collected. If specified, measure data will be collected only for subjects that have a primary relationship to the identified practitioner',
        )]
        public readonly ?string $practitioner = null,
        #[FhirOperationParameter(
            name: 'lastReceivedOn',
            phpName: 'lastReceivedOn',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date the results of this measure were last received. This parameter used to indicate when the last time data for this measure was collected. This information is used to support incremental data collection scenarios',
        )]
        public readonly ?string $lastReceivedOn = null,
    ) {
    }
}
