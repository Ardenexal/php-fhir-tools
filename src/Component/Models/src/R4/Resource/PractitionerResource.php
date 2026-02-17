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
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Practitioner\PractitionerQualification;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Practitioner
 *
 * @description A person who is directly or indirectly involved in the provisioning of healthcare.
 */
#[FhirResource(type: 'Practitioner', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Practitioner', fhirVersion: 'R4')]
class PractitionerResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier An identifier for the person as this agent */
        public array $identifier = [],
        /** @var bool|null active Whether this practitioner's record is in active use */
        public ?bool $active = null,
        /** @var array<HumanName> name The name(s) associated with the practitioner */
        public array $name = [],
        /** @var array<ContactPoint> telecom A contact detail for the practitioner (that apply to all roles) */
        public array $telecom = [],
        /** @var array<Address> address Address(es) of the practitioner that are not role specific (typically home address) */
        public array $address = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        public ?AdministrativeGenderType $gender = null,
        /** @var DatePrimitive|null birthDate The date  on which the practitioner was born */
        public ?DatePrimitive $birthDate = null,
        /** @var array<Attachment> photo Image of the person */
        public array $photo = [],
        /** @var array<PractitionerQualification> qualification Certification, licenses, or training pertaining to the provision of care */
        public array $qualification = [],
        /** @var array<CodeableConcept> communication A language the practitioner can use in patient communication */
        public array $communication = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
