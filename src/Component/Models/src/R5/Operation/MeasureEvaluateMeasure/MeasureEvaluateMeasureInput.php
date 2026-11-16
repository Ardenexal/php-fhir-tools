<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureEvaluateMeasure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MeasureResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ParametersResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-evaluate-measure',
    use: 'in',
    version: 'R5',
    operation: 'MeasureEvaluateMeasure',
    path: '',
)]
final class MeasureEvaluateMeasureInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'measure',
            phpName: 'measure',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Measure',
            documentation: 'The measure to evaluate. If the operation is invoked at the instance level, this parameter is not allowed; if the operation is invoked at the type level, this parameter is required, or a url (and optionally version) must be supplied.',
            scope: ['type'],
        )]
        public readonly ?MeasureResource $measure = null,
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'The url of the plan measure to be applied. If the operation is invoked at the instance level, this parameter is not allowed; if the operation is invoked at the type level, this parameter (and optionally the version), or the measure parameter must be supplied',
            scope: ['type'],
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the measure to be applied. If the operation is invoked at the instance level, this parameter is not allowed; if the operation is invoked at the type level, this parameter may only be used if the url parameter is supplied.',
            scope: ['type'],
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'Subject for which the measure will be calculated. The subject may be a Patient, Practitioner, PractitionerRole, Organization, Location, Device, or Group. Subjects provided in this parameter will be resolved as the subject of the measure based on the type of the subject. If multiple subjects of the same type are provided, the behavior is implementation-defined',
        )]
        public readonly ?string $subject = null,
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
        #[FhirOperationParameter(
            name: 'reportType',
            phpName: 'reportType',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The type of measure report: individual, subject-list, or summary. If not specified, a default value of individual will be used if the subject parameter is supplied, otherwise, summary will be used. NOTE: Implementations should support the use of `subject` for individual and `population` for summary for backwards compatibility with existing implementations.',
        )]
        public readonly ?string $reportType = null,
        #[FhirOperationParameter(
            name: 'provider',
            phpName: 'provider',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The provider for which the report will be run. This may be a reference to a Practitioner, PractitionerRole, or Organization. If specified, the measure will be calculated for subjects that have a primary relationship to the identified provider. How this relationship is determined is implementation-specific.',
        )]
        public readonly ?string $provider = null,
        #[FhirOperationParameter(
            name: 'location',
            phpName: 'location',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The location for which the report will be run.',
        )]
        public readonly ?string $location = null,
        #[FhirOperationParameter(
            name: 'lastReceivedOn',
            phpName: 'lastReceivedOn',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date the results of this measure were last received. This parameter is only valid for patient-level reports and is used to indicate when the last time a result for this patient was received. This information can be used to limit the set of resources returned for a patient-level report',
        )]
        public readonly ?string $lastReceivedOn = null,
        #[FhirOperationParameter(
            name: 'parameters',
            phpName: 'parameters',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Parameters',
            documentation: 'Any input parameters for the evaluation. Parameters defined in this input will be made available by name to the CQL expression. Parameter types are mapped to CQL as specified in the Using CQL topic of the Clinical Reasoning Module. If a parameter appears more than once in the input Parameters resource, it is represented with a List in the input CQL. If a parameter has parts, it is represented as a Tuple in the input CQL.',
        )]
        public readonly ?ParametersResource $parameters = null,
    ) {
    }
}
