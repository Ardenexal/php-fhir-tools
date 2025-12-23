<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Person
 * @description Demographics and administrative information about a person independent of a specific health-related context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Person', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Person', fhirVersion: 'R4')]
class FHIRPerson extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier A human identifier for this person */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRHumanName> name A name associated with the person */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate birthDate The date on which the person was born */
		public ?FHIRDate $birthDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAddress> address One or more addresses for the person */
		public array $address = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment photo Image of the person */
		public ?FHIRAttachment $photo = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference managingOrganization The organization that is the custodian of the person record */
		public ?FHIRReference $managingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean active This person's record is in active use */
		public ?FHIRBoolean $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPersonLink> link Link to a resource that concerns the same actual person */
		public array $link = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
