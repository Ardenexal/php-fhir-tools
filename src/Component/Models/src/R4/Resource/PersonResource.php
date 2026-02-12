<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Person
 * @description Demographics and administrative information about a person independent of a specific health-related context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Person', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Person', fhirVersion: 'R4')]
class PersonResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName> name A name associated with the person */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive birthDate The date on which the person was born */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $birthDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address> address One or more addresses for the person */
		public array $address = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment photo Image of the person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $photo = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference managingOrganization The organization that is the custodian of the person record */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $managingOrganization = null,
		/** @var null|bool active This person's record is in active use */
		public ?bool $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Person\PersonLink> link Link to a resource that concerns the same actual person */
		public array $link = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
