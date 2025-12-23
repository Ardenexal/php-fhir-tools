<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/RelatedPerson
 * @description Information about a person that is involved in a patient's health or the care for a patient, but who is not the target of healthcare, nor has a formal responsibility in the care process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'RelatedPerson', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/RelatedPerson', fhirVersion: 'R5')]
class FHIRRelatedPerson extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier A human identifier for this person */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean active Whether this related person's record is in active use */
		public ?FHIRBoolean $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The patient this person is related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> relationship The relationship of the related person to the patient */
		public array $relationship = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRHumanName> name A name associated with the person */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate birthDate The date on which the related person was born */
		public ?FHIRDate $birthDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress> address Address where the related person can be contacted or visited */
		public array $address = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment> photo Image of the person */
		public array $photo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Period of time that this relationship is considered valid */
		public ?FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedPersonCommunication> communication A language which may be used to communicate with the related person about the patient's health */
		public array $communication = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
