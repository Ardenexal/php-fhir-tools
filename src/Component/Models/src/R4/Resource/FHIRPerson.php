<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Person
 *
 * @description Demographics and administrative information about a person independent of a specific health-related context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Person', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Person', fhirVersion: 'R4')]
class FHIRPerson extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier A human identifier for this person */
        public array $identifier = [],
        /** @var array<FHIRHumanName> name A name associated with the person */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?\FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRDate|null birthDate The date on which the person was born */
        public ?\FHIRDate $birthDate = null,
        /** @var array<FHIRAddress> address One or more addresses for the person */
        public array $address = [],
        /** @var FHIRAttachment|null photo Image of the person */
        public ?\FHIRAttachment $photo = null,
        /** @var FHIRReference|null managingOrganization The organization that is the custodian of the person record */
        public ?\FHIRReference $managingOrganization = null,
        /** @var FHIRBoolean|null active This person's record is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var array<FHIRPersonLink> link Link to a resource that concerns the same actual person */
        public array $link = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
