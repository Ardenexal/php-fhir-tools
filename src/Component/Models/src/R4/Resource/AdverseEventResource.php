<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/AdverseEvent
 * @description Actual or  potential/avoided event causing unintended physical injury resulting from or contributed to by medical care, a research study or other healthcare setting factors that requires additional monitoring, treatment, or hospitalization, or that results in death.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AdverseEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AdverseEvent', fhirVersion: 'R4')]
class AdverseEventResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Business identifier for the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdverseEventActualityType actuality actual | potential */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdverseEventActualityType $actuality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category product-problem | product-quality | product-use-error | wrong-dose | incorrect-prescribing-information | wrong-technique | wrong-route-of-administration | wrong-rate | wrong-duration | wrong-time | expired-drug | medical-device-use-error | problem-different-manufacturer | unsafe-physical-environment */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept event Type of the event itself in relation to the subject */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $event = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Subject impacted by event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date When the event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive detected When the event was detected */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $detected = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive recordedDate When the event was recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $recordedDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> resultingCondition Effect on the subject due to this event */
		public array $resultingCondition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Location where adverse event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept seriousness Seriousness of the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $seriousness = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept severity mild | moderate | severe */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept outcome resolved | recovering | ongoing | resolvedWithSequelae | fatal | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference recorder Who recorded the adverse event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $recorder = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> contributor Who  was involved in the adverse event or the potential adverse event */
		public array $contributor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\AdverseEvent\AdverseEventSuspectEntity> suspectEntity The suspected agent causing the adverse event */
		public array $suspectEntity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> subjectMedicalHistory AdverseEvent.subjectMedicalHistory */
		public array $subjectMedicalHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> referenceDocument AdverseEvent.referenceDocument */
		public array $referenceDocument = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> study AdverseEvent.study */
		public array $study = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
