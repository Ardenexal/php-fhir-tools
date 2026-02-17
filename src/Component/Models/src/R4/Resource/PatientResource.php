<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientCommunication;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientContact;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientLink;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Patient
 *
 * @description Demographics and other administrative information about an individual or animal receiving care or other health-related services.
 */
#[FhirResource(type: 'Patient', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R4')]
class PatientResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier An identifier for this patient */
        public array $identifier = [],
        /** @var bool|null active Whether this patient's record is in active use */
        public ?bool $active = null,
        /** @var array<HumanName> name A name associated with the patient */
        public array $name = [],
        /** @var array<ContactPoint> telecom A contact detail for the individual */
        public array $telecom = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        public ?AdministrativeGenderType $gender = null,
        /** @var DatePrimitive|null birthDate The date of birth for the individual */
        public ?DatePrimitive $birthDate = null,
        /** @var bool|DateTimePrimitive|null deceasedX Indicates if the individual is deceased or not */
        public bool|DateTimePrimitive|null $deceasedX = null,
        /** @var array<Address> address An address for the individual */
        public array $address = [],
        /** @var CodeableConcept|null maritalStatus Marital (civil) status of a patient */
        public ?CodeableConcept $maritalStatus = null,
        /** @var bool|int|null multipleBirthX Whether patient is part of a multiple birth */
        public bool|int|null $multipleBirthX = null,
        /** @var array<Attachment> photo Image of the patient */
        public array $photo = [],
        /** @var array<PatientContact> contact A contact party (e.g. guardian, partner, friend) for the patient */
        public array $contact = [],
        /** @var array<PatientCommunication> communication A language which may be used to communicate with the patient about his or her health */
        public array $communication = [],
        /** @var array<Reference> generalPractitioner Patient's nominated primary care provider */
        public array $generalPractitioner = [],
        /** @var Reference|null managingOrganization Organization that is the custodian of the patient record */
        public ?Reference $managingOrganization = null,
        /** @var array<PatientLink> link Link to another patient resource that concerns the same actual person */
        public array $link = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
