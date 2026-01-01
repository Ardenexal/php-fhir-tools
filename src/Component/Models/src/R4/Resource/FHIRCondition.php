<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Condition
 * @description A clinical condition, problem, diagnosis, or other event, situation, issue, or clinical concept that has risen to a level of concern.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Condition', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Condition', fhirVersion: 'R4')]
class FHIRCondition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier External Ids for this condition */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept clinicalStatus active | recurrence | relapse | inactive | remission | resolved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $clinicalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept verificationStatus unconfirmed | provisional | differential | confirmed | refuted | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $verificationStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> category problem-list-item | encounter-diagnosis */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept severity Subjective severity of condition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Identification of the condition, problem or diagnosis */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> bodySite Anatomical location, if relevant */
		public array $bodySite = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subject Who has the condition? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string onsetX Estimated or actual date,  date-time, or age */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $onsetX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string abatementX When in resolution/remission */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $abatementX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime recordedDate Date record was first recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $recordedDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference recorder Who recorded the condition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $recorder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference asserter Person who asserts this condition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $asserter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConditionStage> stage Stage/grade, usually assessed formally */
		public array $stage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConditionEvidence> evidence Supporting evidence */
		public array $evidence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Additional information about the Condition */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
