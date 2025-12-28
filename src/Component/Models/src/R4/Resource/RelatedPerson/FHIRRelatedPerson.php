<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedPerson
 *
 * @description Information about a person that is involved in the care for a patient, but who is not the target of healthcare, nor has a formal responsibility in the care process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'RelatedPerson', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/RelatedPerson', fhirVersion: 'R4')]
class FHIRRelatedPerson extends FHIRDomainResource
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
        /** @var FHIRBoolean|null active Whether this related person's record is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var FHIRReference|null patient The patient this person is related to */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
        /** @var array<FHIRCodeableConcept> relationship The nature of the relationship */
        public array $relationship = [],
        /** @var array<FHIRHumanName> name A name associated with the person */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?\FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRDate|null birthDate The date on which the related person was born */
        public ?\FHIRDate $birthDate = null,
        /** @var array<FHIRAddress> address Address where the related person can be contacted or visited */
        public array $address = [],
        /** @var array<FHIRAttachment> photo Image of the person */
        public array $photo = [],
        /** @var FHIRPeriod|null period Period of time that this relationship is considered valid */
        public ?\FHIRPeriod $period = null,
        /** @var array<FHIRRelatedPersonCommunication> communication A language which may be used to communicate with about the patient's health */
        public array $communication = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
