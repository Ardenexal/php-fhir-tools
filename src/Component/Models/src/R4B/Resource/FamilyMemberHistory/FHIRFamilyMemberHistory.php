<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/FamilyMemberHistory
 * @description Significant health conditions for a person related to the patient relevant in the context of care for the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'FamilyMemberHistory',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/FamilyMemberHistory',
	fhirVersion: 'R4B',
)]
class FHIRFamilyMemberHistory extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier External Id(s) for this record */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFamilyHistoryStatusType status partial | completed | entered-in-error | health-unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFamilyHistoryStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept dataAbsentReason subject-unknown | withheld | unable-to-obtain | deferred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $dataAbsentReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference patient Patient history is about */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime date When history was recorded or last updated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string name The family member described */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept relationship Relationship to the subject */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept sex male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $sex = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string bornX (approximate) date of birth */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $bornX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string ageX (approximate) age */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $ageX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean estimatedAge Age is estimated? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $estimatedAge = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string deceasedX Dead? How old/when? */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $deceasedX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> reasonCode Why was family member history performed? */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> reasonReference Why was family member history performed? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note General note about related person */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFamilyMemberHistoryCondition> condition Condition that the related person had */
		public array $condition = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
