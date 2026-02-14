<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Patient
 * @description Demographics and other administrative information about an individual or animal receiving care or other health-related services.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Patient', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R4')]
class PatientResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier An identifier for this patient */
		public array $identifier = [],
		/** @var null|bool active Whether this patient's record is in active use */
		public ?bool $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName> name A name associated with the patient */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom A contact detail for the individual */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive birthDate The date of birth for the individual */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $birthDate = null,
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive deceasedX Indicates if the individual is deceased or not */
		public bool|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|null $deceasedX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address> address An address for the individual */
		public array $address = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept maritalStatus Marital (civil) status of a patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $maritalStatus = null,
		/** @var null|bool|int multipleBirthX Whether patient is part of a multiple birth */
		public bool|int|null $multipleBirthX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment> photo Image of the patient */
		public array $photo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientContact> contact A contact party (e.g. guardian, partner, friend) for the patient */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientCommunication> communication A language which may be used to communicate with the patient about his or her health */
		public array $communication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> generalPractitioner Patient's nominated primary care provider */
		public array $generalPractitioner = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference managingOrganization Organization that is the custodian of the patient record */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $managingOrganization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientLink> link Link to another patient resource that concerns the same actual person */
		public array $link = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
