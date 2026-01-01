<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Person
 *
 * @description Demographics and administrative information about a person independent of a specific health-related context.
 */
#[FhirResource(type: 'Person', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Person', fhirVersion: 'R5')]
class FHIRPerson extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier A human identifier for this person */
        public array $identifier = [],
        /** @var FHIRBoolean|null active This person's record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var array<FHIRHumanName> name A name associated with the person */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRDate|null birthDate The date on which the person was born */
        public ?FHIRDate $birthDate = null,
        /** @var FHIRBoolean|FHIRDateTime|null deceasedX Indicates if the individual is deceased or not */
        public FHIRBoolean|FHIRDateTime|null $deceasedX = null,
        /** @var array<FHIRAddress> address One or more addresses for the person */
        public array $address = [],
        /** @var FHIRCodeableConcept|null maritalStatus Marital (civil) status of a person */
        public ?FHIRCodeableConcept $maritalStatus = null,
        /** @var array<FHIRAttachment> photo Image of the person */
        public array $photo = [],
        /** @var array<FHIRPersonCommunication> communication A language which may be used to communicate with the person about his or her health */
        public array $communication = [],
        /** @var FHIRReference|null managingOrganization The organization that is the custodian of the person record */
        public ?FHIRReference $managingOrganization = null,
        /** @var array<FHIRPersonLink> link Link to a resource that concerns the same actual person */
        public array $link = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
