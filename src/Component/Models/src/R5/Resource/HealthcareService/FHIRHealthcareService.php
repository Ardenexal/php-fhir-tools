<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/HealthcareService
 * @description The details of a healthcare service available at a location.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'HealthcareService',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/HealthcareService',
	fhirVersion: 'R5',
)]
class FHIRHealthcareService extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier External identifiers for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean active Whether this HealthcareService record is in active use */
		public ?FHIRBoolean $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference providedBy Organization that provides this service */
		public ?FHIRReference $providedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> offeredIn The service within which this service is offered */
		public array $offeredIn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Broad category of service being performed or delivered */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type Type of service that may be delivered or performed */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> specialty Specialties handled by the HealthcareService */
		public array $specialty = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> location Location(s) where service may be provided */
		public array $location = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Description of service as presented to a consumer while searching */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown comment Additional description and/or any specific issues not covered elsewhere */
		public ?FHIRMarkdown $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown extraDetails Extra details about the service that can't be placed in the other fields */
		public ?FHIRMarkdown $extraDetails = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment photo Facilitates quick identification of the service */
		public ?FHIRAttachment $photo = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtendedContactDetail> contact Official contact details for the HealthcareService */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> coverageArea Location(s) service is intended for/available to */
		public array $coverageArea = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> serviceProvisionCode Conditions under which service is available/offered */
		public array $serviceProvisionCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRHealthcareServiceEligibility> eligibility Specific eligibility requirements required to use the service */
		public array $eligibility = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> program Programs that this service is applicable to */
		public array $program = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
		public array $characteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> communication The language that this service is offered in */
		public array $communication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> referralMethod Ways that the service accepts referrals */
		public array $referralMethod = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean appointmentRequired If an appointment is required for access to this service */
		public ?FHIRBoolean $appointmentRequired = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAvailability> availability Times the healthcare service is available (including exceptions) */
		public array $availability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> endpoint Technical endpoints providing access to electronic services operated for the healthcare service */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
