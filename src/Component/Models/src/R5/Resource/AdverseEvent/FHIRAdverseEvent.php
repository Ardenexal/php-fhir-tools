<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 * @description An event (i.e. any change to current patient status) that may be related to unintended effects on a patient or research participant. The unintended effects may require additional monitoring, treatment, hospitalization, or may result in death. The AdverseEvent resource also extends to potential or avoided events that could have had such effects. There are two major domains where the AdverseEvent resource is expected to be used. One is in clinical care reported adverse events and the other is in reporting adverse events in clinical  research trial management. Adverse events can be reported by healthcare providers, patients, caregivers or by medical products manufacturers. Given the differences between these two concepts, we recommend consulting the domain specific implementation guides when implementing the AdverseEvent Resource. The implementation guides include specific extensions, value sets and constraints.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AdverseEvent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R5')]
class FHIRAdverseEvent extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for the event */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventStatusType status in-progress | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAdverseEventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventActualityType actuality actual | potential */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAdverseEventActualityType $actuality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category wrong-patient | procedure-mishap | medication-mishap | device | unsafe-physical-environment | hospital-aquired-infection | wrong-body-site */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Event or incident that occurred or was averted */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Subject impacted by event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter The Encounter associated with the start of the AdverseEvent */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming occurrenceX When the event occurred */
		public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime detected When the event was detected */
		public ?FHIRDateTime $detected = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime recordedDate When the event was recorded */
		public ?FHIRDateTime $recordedDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> resultingEffect Effect on the subject due to this event */
		public array $resultingEffect = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Location where adverse event occurred */
		public ?FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept seriousness Seriousness or gravity of the event */
		public ?FHIRCodeableConcept $seriousness = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> outcome Type of outcome from the adverse event */
		public array $outcome = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference recorder Who recorded the adverse event */
		public ?FHIRReference $recorder = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventParticipant> participant Who was involved in the adverse event or the potential adverse event and what they did */
		public array $participant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> study Research study that the subject is enrolled in */
		public array $study = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean expectedInResearchStudy Considered likely or probable or anticipated in the research study */
		public ?FHIRBoolean $expectedInResearchStudy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
		public array $suspectEntity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventContributingFactor> contributingFactor Contributing factors suspected to have increased the probability or severity of the adverse event */
		public array $contributingFactor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventPreventiveAction> preventiveAction Preventive actions that contributed to avoiding the adverse event */
		public array $preventiveAction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventMitigatingAction> mitigatingAction Ameliorating actions taken after the adverse event occured in order to reduce the extent of harm */
		public array $mitigatingAction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdverseEventSupportingInfo> supportingInfo Supporting information relevant to the event */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comment on adverse event */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
