<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/RelatedPerson
 * @description Information about a person that is involved in the care for a patient, but who is not the target of healthcare, nor has a formal responsibility in the care process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'RelatedPerson', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/RelatedPerson', fhirVersion: 'R4')]
class RelatedPersonResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier A human identifier for this person */
		public array $identifier = [],
		/** @var null|bool active Whether this related person's record is in active use */
		public ?bool $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient The patient this person is related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> relationship The nature of the relationship */
		public array $relationship = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName> name A name associated with the person */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive birthDate The date on which the related person was born */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $birthDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address> address Address where the related person can be contacted or visited */
		public array $address = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment> photo Image of the person */
		public array $photo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Period of time that this relationship is considered valid */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\RelatedPerson\RelatedPersonCommunication> communication A language which may be used to communicate with about the patient's health */
		public array $communication = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
