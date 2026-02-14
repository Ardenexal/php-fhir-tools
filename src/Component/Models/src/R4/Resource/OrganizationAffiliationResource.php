<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation
 * @description Defines an affiliation/assotiation/relationship between 2 distinct oganizations, that is not a part-of relationship/sub-division relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'OrganizationAffiliation',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/OrganizationAffiliation',
	fhirVersion: 'R4',
)]
class OrganizationAffiliationResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifiers that are specific to this role */
		public array $identifier = [],
		/** @var null|bool active Whether this organization affiliation record is in active use */
		public ?bool $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period The period during which the participatingOrganization is affiliated with the primary organization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference organization Organization where the role is available */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference participatingOrganization Organization that provides/performs the role (e.g. providing services or is a member of) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $participatingOrganization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> network Health insurance provider network in which the participatingOrganization provides the role's services (if defined) at the indicated locations (if defined) */
		public array $network = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> code Definition of the role the participatingOrganization plays */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> specialty Specific specialty of the participatingOrganization in the context of the role */
		public array $specialty = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> location The location(s) at which the role occurs */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> healthcareService Healthcare services provided through the role */
		public array $healthcareService = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom Contact details at the participatingOrganization relevant to this Affiliation */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> endpoint Technical endpoints providing access to services operated for this role */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
