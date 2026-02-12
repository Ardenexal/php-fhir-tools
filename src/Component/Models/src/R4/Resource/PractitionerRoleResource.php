<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/PractitionerRole
 * @description A specific set of Roles/Locations/specialties/services that a practitioner may perform at an organization for a period of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PractitionerRole',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
	fhirVersion: 'R4',
)]
class PractitionerRoleResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifiers that are specific to a role/location */
		public array $identifier = [],
		/** @var null|bool active Whether this practitioner role record is in active use */
		public ?bool $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period The period during which the practitioner is authorized to perform in these role(s) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference practitioner Practitioner that is able to provide the defined services for the organization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $practitioner = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference organization Organization where the roles are available */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $organization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> code Roles which this practitioner may perform */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> specialty Specific specialty of the practitioner */
		public array $specialty = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> location The location(s) at which this practitioner provides care */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> healthcareService The list of healthcare services that this worker provides for this role's Organization/Location(s) */
		public array $healthcareService = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom Contact details that are specific to the role/location/service */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleAvailableTime> availableTime Times the Service Site is available */
		public array $availableTime = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole\PractitionerRoleNotAvailable> notAvailable Not available during this time due to provided reason */
		public array $notAvailable = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string availabilityExceptions Description of availability exceptions */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $availabilityExceptions = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> endpoint Technical endpoints providing access to services operated for the practitioner with this role */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
