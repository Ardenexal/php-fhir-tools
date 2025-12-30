<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Patient
 *
 * @description Demographics and other administrative information about an individual or animal receiving care or other health-related services.
 */
#[FhirResource(type: 'Patient', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R4B')]
class FHIRPatient extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier for this patient */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this patient's record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var array<FHIRHumanName> name A name associated with the patient */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the individual */
        public array $telecom = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRDate|null birthDate The date of birth for the individual */
        public ?FHIRDate $birthDate = null,
        /** @var FHIRBoolean|FHIRDateTime|null deceasedX Indicates if the individual is deceased or not */
        public FHIRBoolean|FHIRDateTime|null $deceasedX = null,
        /** @var array<FHIRAddress> address An address for the individual */
        public array $address = [],
        /** @var FHIRCodeableConcept|null maritalStatus Marital (civil) status of a patient */
        public ?FHIRCodeableConcept $maritalStatus = null,
        /** @var FHIRBoolean|FHIRInteger|null multipleBirthX Whether patient is part of a multiple birth */
        public FHIRBoolean|FHIRInteger|null $multipleBirthX = null,
        /** @var array<FHIRAttachment> photo Image of the patient */
        public array $photo = [],
        /** @var array<FHIRPatientContact> contact A contact party (e.g. guardian, partner, friend) for the patient */
        public array $contact = [],
        /** @var array<FHIRPatientCommunication> communication A language which may be used to communicate with the patient about his or her health */
        public array $communication = [],
        /** @var array<FHIRReference> generalPractitioner Patient's nominated primary care provider */
        public array $generalPractitioner = [],
        /** @var FHIRReference|null managingOrganization Organization that is the custodian of the patient record */
        public ?FHIRReference $managingOrganization = null,
        /** @var array<FHIRPatientLink> link Link to another patient resource that concerns the same actual person */
        public array $link = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
