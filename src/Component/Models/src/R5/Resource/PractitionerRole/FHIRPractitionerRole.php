<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/PractitionerRole
 * @description A specific set of Roles/Locations/specialties/services that a practitioner may perform at an organization for a period of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PractitionerRole',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
	fhirVersion: 'R5',
)]
class FHIRPractitionerRole extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Identifiers for a role/location */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean active Whether this practitioner role record is in active use */
		public ?FHIRBoolean $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period The period during which the practitioner is authorized to perform in these role(s) */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference practitioner Practitioner that provides services for the organization */
		public ?FHIRReference $practitioner = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference organization Organization where the roles are available */
		public ?FHIRReference $organization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> code Roles which this practitioner may perform */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> specialty Specific specialty of the practitioner */
		public array $specialty = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> location Location(s) where the practitioner provides care */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> healthcareService Healthcare services provided for this role's Organization/Location(s) */
		public array $healthcareService = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtendedContactDetail> contact Official contact details relating to this PractitionerRole */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
		public array $characteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> communication A language the practitioner (in this role) can use in patient communication */
		public array $communication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAvailability> availability Times the Practitioner is available at this location and/or healthcare service (including exceptions) */
		public array $availability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> endpoint Endpoints for interacting with the practitioner in this role */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
