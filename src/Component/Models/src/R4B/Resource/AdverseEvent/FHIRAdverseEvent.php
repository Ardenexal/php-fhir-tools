<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdverseEventActualityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 *
 * @description Actual or  potential/avoided event causing unintended physical injury resulting from or contributed to by medical care, a research study or other healthcare setting factors that requires additional monitoring, treatment, or hospitalization, or that results in death.
 */
#[FhirResource(type: 'AdverseEvent', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R4B')]
class FHIRAdverseEvent extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Business identifier for the event */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRAdverseEventActualityType|null actuality actual | potential */
        #[NotBlank]
        public ?FHIRAdverseEventActualityType $actuality = null,
        /** @var array<FHIRCodeableConcept> category product-problem | product-quality | product-use-error | wrong-dose | incorrect-prescribing-information | wrong-technique | wrong-route-of-administration | wrong-rate | wrong-duration | wrong-time | expired-drug | medical-device-use-error | problem-different-manufacturer | unsafe-physical-environment */
        public array $category = [],
        /** @var FHIRCodeableConcept|null event Type of the event itself in relation to the subject */
        public ?FHIRCodeableConcept $event = null,
        /** @var FHIRReference|null subject Subject impacted by event */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null date When the event occurred */
        public ?FHIRDateTime $date = null,
        /** @var FHIRDateTime|null detected When the event was detected */
        public ?FHIRDateTime $detected = null,
        /** @var FHIRDateTime|null recordedDate When the event was recorded */
        public ?FHIRDateTime $recordedDate = null,
        /** @var array<FHIRReference> resultingCondition Effect on the subject due to this event */
        public array $resultingCondition = [],
        /** @var FHIRReference|null location Location where adverse event occurred */
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null seriousness Seriousness of the event */
        public ?FHIRCodeableConcept $seriousness = null,
        /** @var FHIRCodeableConcept|null severity mild | moderate | severe */
        public ?FHIRCodeableConcept $severity = null,
        /** @var FHIRCodeableConcept|null outcome resolved | recovering | ongoing | resolvedWithSequelae | fatal | unknown */
        public ?FHIRCodeableConcept $outcome = null,
        /** @var FHIRReference|null recorder Who recorded the adverse event */
        public ?FHIRReference $recorder = null,
        /** @var array<FHIRReference> contributor Who  was involved in the adverse event or the potential adverse event */
        public array $contributor = [],
        /** @var array<FHIRAdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
        public array $suspectEntity = [],
        /** @var array<FHIRReference> subjectMedicalHistory AdverseEvent.subjectMedicalHistory */
        public array $subjectMedicalHistory = [],
        /** @var array<FHIRReference> referenceDocument AdverseEvent.referenceDocument */
        public array $referenceDocument = [],
        /** @var array<FHIRReference> study AdverseEvent.study */
        public array $study = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
