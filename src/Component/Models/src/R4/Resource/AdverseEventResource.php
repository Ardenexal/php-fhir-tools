<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AdverseEventActualityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AdverseEvent\AdverseEventSuspectEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 *
 * @description Actual or  potential/avoided event causing unintended physical injury resulting from or contributed to by medical care, a research study or other healthcare setting factors that requires additional monitoring, treatment, or hospitalization, or that results in death.
 */
#[FhirResource(type: 'AdverseEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R4')]
class AdverseEventResource extends DomainResourceResource
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
        /** @var Identifier|null identifier Business identifier for the event */
        public ?Identifier $identifier = null,
        /** @var AdverseEventActualityType|null actuality actual | potential */
        #[NotBlank]
        public ?AdverseEventActualityType $actuality = null,
        /** @var array<CodeableConcept> category product-problem | product-quality | product-use-error | wrong-dose | incorrect-prescribing-information | wrong-technique | wrong-route-of-administration | wrong-rate | wrong-duration | wrong-time | expired-drug | medical-device-use-error | problem-different-manufacturer | unsafe-physical-environment */
        public array $category = [],
        /** @var CodeableConcept|null event Type of the event itself in relation to the subject */
        public ?CodeableConcept $event = null,
        /** @var Reference|null subject Subject impacted by event */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null date When the event occurred */
        public ?DateTimePrimitive $date = null,
        /** @var DateTimePrimitive|null detected When the event was detected */
        public ?DateTimePrimitive $detected = null,
        /** @var DateTimePrimitive|null recordedDate When the event was recorded */
        public ?DateTimePrimitive $recordedDate = null,
        /** @var array<Reference> resultingCondition Effect on the subject due to this event */
        public array $resultingCondition = [],
        /** @var Reference|null location Location where adverse event occurred */
        public ?Reference $location = null,
        /** @var CodeableConcept|null seriousness Seriousness of the event */
        public ?CodeableConcept $seriousness = null,
        /** @var CodeableConcept|null severity mild | moderate | severe */
        public ?CodeableConcept $severity = null,
        /** @var CodeableConcept|null outcome resolved | recovering | ongoing | resolvedWithSequelae | fatal | unknown */
        public ?CodeableConcept $outcome = null,
        /** @var Reference|null recorder Who recorded the adverse event */
        public ?Reference $recorder = null,
        /** @var array<Reference> contributor Who  was involved in the adverse event or the potential adverse event */
        public array $contributor = [],
        /** @var array<AdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
        public array $suspectEntity = [],
        /** @var array<Reference> subjectMedicalHistory AdverseEvent.subjectMedicalHistory */
        public array $subjectMedicalHistory = [],
        /** @var array<Reference> referenceDocument AdverseEvent.referenceDocument */
        public array $referenceDocument = [],
        /** @var array<Reference> study AdverseEvent.study */
        public array $study = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
