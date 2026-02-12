<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Practitioner
 * @description A person who is directly or indirectly involved in the provisioning of healthcare.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Practitioner', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Practitioner', fhirVersion: 'R4')]
class PractitionerResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier An identifier for the person as this agent */
		public array $identifier = [],
		/** @var null|bool active Whether this practitioner's record is in active use */
		public ?bool $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName> name The name(s) associated with the practitioner */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom A contact detail for the practitioner (that apply to all roles) */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address> address Address(es) of the practitioner that are not role specific (typically home address) */
		public array $address = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive birthDate The date  on which the practitioner was born */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $birthDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment> photo Image of the person */
		public array $photo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Practitioner\PractitionerQualification> qualification Certification, licenses, or training pertaining to the provision of care */
		public array $qualification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> communication A language the practitioner can use in patient communication */
		public array $communication = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
