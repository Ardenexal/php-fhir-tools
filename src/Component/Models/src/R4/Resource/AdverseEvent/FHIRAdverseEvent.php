<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 * @description Actual or  potential/avoided event causing unintended physical injury resulting from or contributed to by medical care, a research study or other healthcare setting factors that requires additional monitoring, treatment, or hospitalization, or that results in death.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AdverseEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R4')]
class FHIRAdverseEvent extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier identifier Business identifier for the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAdverseEventActualityType actuality actual | potential */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAdverseEventActualityType $actuality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> category product-problem | product-quality | product-use-error | wrong-dose | incorrect-prescribing-information | wrong-technique | wrong-route-of-administration | wrong-rate | wrong-duration | wrong-time | expired-drug | medical-device-use-error | problem-different-manufacturer | unsafe-physical-environment */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept event Type of the event itself in relation to the subject */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $event = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subject Subject impacted by event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime date When the event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime detected When the event was detected */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $detected = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime recordedDate When the event was recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $recordedDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> resultingCondition Effect on the subject due to this event */
		public array $resultingCondition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference location Location where adverse event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept seriousness Seriousness of the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $seriousness = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept severity mild | moderate | severe */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept outcome resolved | recovering | ongoing | resolvedWithSequelae | fatal | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference recorder Who recorded the adverse event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $recorder = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> contributor Who  was involved in the adverse event or the potential adverse event */
		public array $contributor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
		public array $suspectEntity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> subjectMedicalHistory AdverseEvent.subjectMedicalHistory */
		public array $subjectMedicalHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> referenceDocument AdverseEvent.referenceDocument */
		public array $referenceDocument = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> study AdverseEvent.study */
		public array $study = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
