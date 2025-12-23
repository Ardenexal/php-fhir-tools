<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Patient
 * @description Demographics and other administrative information about an individual or animal receiving care or other health-related services.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Patient', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R5')]
class FHIRPatient extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier An identifier for this patient */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean active Whether this patient's record is in active use */
		public ?FHIRBoolean $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRHumanName> name A name associated with the patient */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint> telecom A contact detail for the individual */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate birthDate The date of birth for the individual */
		public ?FHIRDate $birthDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime deceasedX Indicates if the individual is deceased or not */
		public FHIRBoolean|FHIRDateTime|null $deceasedX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress> address An address for the individual */
		public array $address = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept maritalStatus Marital (civil) status of a patient */
		public ?FHIRCodeableConcept $maritalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger multipleBirthX Whether patient is part of a multiple birth */
		public FHIRBoolean|FHIRInteger|null $multipleBirthX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment> photo Image of the patient */
		public array $photo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPatientContact> contact A contact party (e.g. guardian, partner, friend) for the patient */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPatientCommunication> communication A language which may be used to communicate with the patient about his or her health */
		public array $communication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> generalPractitioner Patient's nominated primary care provider */
		public array $generalPractitioner = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference managingOrganization Organization that is the custodian of the patient record */
		public ?FHIRReference $managingOrganization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPatientLink> link Link to a Patient or RelatedPerson resource that concerns the same actual individual */
		public array $link = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
