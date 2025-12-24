<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 *
 * @description An event (i.e. any change to current patient status) that may be related to unintended effects on a patient or research participant. The unintended effects may require additional monitoring, treatment, hospitalization, or may result in death. The AdverseEvent resource also extends to potential or avoided events that could have had such effects. There are two major domains where the AdverseEvent resource is expected to be used. One is in clinical care reported adverse events and the other is in reporting adverse events in clinical  research trial management. Adverse events can be reported by healthcare providers, patients, caregivers or by medical products manufacturers. Given the differences between these two concepts, we recommend consulting the domain specific implementation guides when implementing the AdverseEvent Resource. The implementation guides include specific extensions, value sets and constraints.
 */
#[FhirResource(type: 'AdverseEvent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R5')]
class FHIRAdverseEvent extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for the event */
        public array $identifier = [],
        /** @var FHIRAdverseEventStatusType|null status in-progress | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRAdverseEventStatusType $status = null,
        /** @var FHIRAdverseEventActualityType|null actuality actual | potential */
        #[NotBlank]
        public ?FHIRAdverseEventActualityType $actuality = null,
        /** @var array<FHIRCodeableConcept> category wrong-patient | procedure-mishap | medication-mishap | device | unsafe-physical-environment | hospital-aquired-infection | wrong-body-site */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Event or incident that occurred or was averted */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Subject impacted by event */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter The Encounter associated with the start of the AdverseEvent */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When the event occurred */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRDateTime|null detected When the event was detected */
        public ?FHIRDateTime $detected = null,
        /** @var FHIRDateTime|null recordedDate When the event was recorded */
        public ?FHIRDateTime $recordedDate = null,
        /** @var array<FHIRReference> resultingEffect Effect on the subject due to this event */
        public array $resultingEffect = [],
        /** @var FHIRReference|null location Location where adverse event occurred */
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null seriousness Seriousness or gravity of the event */
        public ?FHIRCodeableConcept $seriousness = null,
        /** @var array<FHIRCodeableConcept> outcome Type of outcome from the adverse event */
        public array $outcome = [],
        /** @var FHIRReference|null recorder Who recorded the adverse event */
        public ?FHIRReference $recorder = null,
        /** @var array<FHIRAdverseEventParticipant> participant Who was involved in the adverse event or the potential adverse event and what they did */
        public array $participant = [],
        /** @var array<FHIRReference> study Research study that the subject is enrolled in */
        public array $study = [],
        /** @var FHIRBoolean|null expectedInResearchStudy Considered likely or probable or anticipated in the research study */
        public ?FHIRBoolean $expectedInResearchStudy = null,
        /** @var array<FHIRAdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
        public array $suspectEntity = [],
        /** @var array<FHIRAdverseEventContributingFactor> contributingFactor Contributing factors suspected to have increased the probability or severity of the adverse event */
        public array $contributingFactor = [],
        /** @var array<FHIRAdverseEventPreventiveAction> preventiveAction Preventive actions that contributed to avoiding the adverse event */
        public array $preventiveAction = [],
        /** @var array<FHIRAdverseEventMitigatingAction> mitigatingAction Ameliorating actions taken after the adverse event occured in order to reduce the extent of harm */
        public array $mitigatingAction = [],
        /** @var array<FHIRAdverseEventSupportingInfo> supportingInfo Supporting information relevant to the event */
        public array $supportingInfo = [],
        /** @var array<FHIRAnnotation> note Comment on adverse event */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
