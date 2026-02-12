<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/HealthcareService
 * @description The details of a healthcare service available at a location.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'HealthcareService',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/HealthcareService',
	fhirVersion: 'R4',
)]
class HealthcareServiceResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External identifiers for this item */
		public array $identifier = [],
		/** @var null|bool active Whether this HealthcareService record is in active use */
		public ?bool $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference providedBy Organization that provides this service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $providedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Broad category of service being performed or delivered */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Type of service that may be delivered or performed */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> specialty Specialties handled by the HealthcareService */
		public array $specialty = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> location Location(s) where service may be provided */
		public array $location = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Description of service as presented to a consumer while searching */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string comment Additional description and/or any specific issues not covered elsewhere */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive extraDetails Extra details about the service that can't be placed in the other fields */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $extraDetails = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment photo Facilitates quick identification of the service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $photo = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom Contacts related to the healthcare service */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> coverageArea Location(s) service is intended for/available to */
		public array $coverageArea = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> serviceProvisionCode Conditions under which service is available/offered */
		public array $serviceProvisionCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceEligibility> eligibility Specific eligibility requirements required to use the service */
		public array $eligibility = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> program Programs that this service is applicable to */
		public array $program = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> characteristic Collection of characteristics (attributes) */
		public array $characteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> communication The language that this service is offered in */
		public array $communication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> referralMethod Ways that the service accepts referrals */
		public array $referralMethod = [],
		/** @var null|bool appointmentRequired If an appointment is required for access to this service */
		public ?bool $appointmentRequired = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceAvailableTime> availableTime Times the Service Site is available */
		public array $availableTime = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceNotAvailable> notAvailable Not available during this time due to provided reason */
		public array $notAvailable = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string availabilityExceptions Description of availability exceptions */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $availabilityExceptions = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> endpoint Technical endpoints providing access to electronic services operated for the healthcare service */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
