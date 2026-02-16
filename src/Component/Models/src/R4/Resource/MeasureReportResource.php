<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MeasureReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MeasureReportTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport\MeasureReportGroup;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Quality Information)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MeasureReport
 *
 * @description The MeasureReport resource contains the results of the calculation of a measure; and optionally a reference to the resources involved in that calculation.
 */
#[FhirResource(type: 'MeasureReport', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/MeasureReport', fhirVersion: 'R4')]
class MeasureReportResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Additional identifier for the MeasureReport */
        public array $identifier = [],
        /** @var MeasureReportStatusType|null status complete | pending | error */
        #[NotBlank]
        public ?MeasureReportStatusType $status = null,
        /** @var MeasureReportTypeType|null type individual | subject-list | summary | data-collection */
        #[NotBlank]
        public ?MeasureReportTypeType $type = null,
        /** @var CanonicalPrimitive|null measure What measure was calculated */
        #[NotBlank]
        public ?CanonicalPrimitive $measure = null,
        /** @var Reference|null subject What individual(s) the report is for */
        public ?Reference $subject = null,
        /** @var DateTimePrimitive|null date When the report was generated */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null reporter Who is reporting the data */
        public ?Reference $reporter = null,
        /** @var Period|null period What period the report covers */
        #[NotBlank]
        public ?Period $period = null,
        /** @var CodeableConcept|null improvementNotation increase | decrease */
        public ?CodeableConcept $improvementNotation = null,
        /** @var array<MeasureReportGroup> group Measure results for each group */
        public array $group = [],
        /** @var array<Reference> evaluatedResource What data was used to calculate the measure score */
        public array $evaluatedResource = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
