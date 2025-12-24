<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Practitioner
 *
 * @description A person who is directly or indirectly involved in the provisioning of healthcare.
 */
#[FhirResource(type: 'Practitioner', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Practitioner', fhirVersion: 'R4B')]
class FHIRPractitioner extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier An identifier for the person as this agent */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this practitioner's record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var array<FHIRHumanName> name The name(s) associated with the practitioner */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the practitioner (that apply to all roles) */
        public array $telecom = [],
        /** @var array<FHIRAddress> address Address(es) of the practitioner that are not role specific (typically home address) */
        public array $address = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRDate|null birthDate The date  on which the practitioner was born */
        public ?FHIRDate $birthDate = null,
        /** @var array<FHIRAttachment> photo Image of the person */
        public array $photo = [],
        /** @var array<FHIRPractitionerQualification> qualification Certification, licenses, or training pertaining to the provision of care */
        public array $qualification = [],
        /** @var array<FHIRCodeableConcept> communication A language the practitioner can use in patient communication */
        public array $communication = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
