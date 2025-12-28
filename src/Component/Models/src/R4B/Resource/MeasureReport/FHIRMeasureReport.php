<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Quality Information)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MeasureReport
 *
 * @description The MeasureReport resource contains the results of the calculation of a measure; and optionally a reference to the resources involved in that calculation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MeasureReport',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MeasureReport',
    fhirVersion: 'R4B',
)]
class FHIRMeasureReport extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Additional identifier for the MeasureReport */
        public array $identifier = [],
        /** @var FHIRMeasureReportStatusType|null status complete | pending | error */
        #[NotBlank]
        public ?\FHIRMeasureReportStatusType $status = null,
        /** @var FHIRMeasureReportTypeType|null type individual | subject-list | summary | data-collection */
        #[NotBlank]
        public ?\FHIRMeasureReportTypeType $type = null,
        /** @var FHIRCanonical|null measure What measure was calculated */
        #[NotBlank]
        public ?\FHIRCanonical $measure = null,
        /** @var FHIRReference|null subject What individual(s) the report is for */
        public ?\FHIRReference $subject = null,
        /** @var FHIRDateTime|null date When the report was generated */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRReference|null reporter Who is reporting the data */
        public ?\FHIRReference $reporter = null,
        /** @var FHIRPeriod|null period What period the report covers */
        #[NotBlank]
        public ?\FHIRPeriod $period = null,
        /** @var FHIRCodeableConcept|null improvementNotation increase | decrease */
        public ?\FHIRCodeableConcept $improvementNotation = null,
        /** @var array<FHIRMeasureReportGroup> group Measure results for each group */
        public array $group = [],
        /** @var array<FHIRReference> evaluatedResource What data was used to calculate the measure score */
        public array $evaluatedResource = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
