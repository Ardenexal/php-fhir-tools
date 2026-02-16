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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RelatedPerson\RelatedPersonCommunication;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedPerson
 *
 * @description Information about a person that is involved in the care for a patient, but who is not the target of healthcare, nor has a formal responsibility in the care process.
 */
#[FhirResource(type: 'RelatedPerson', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/RelatedPerson', fhirVersion: 'R4')]
class RelatedPersonResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier A human identifier for this person */
        public array $identifier = [],
        /** @var bool|null active Whether this related person's record is in active use */
        public ?bool $active = null,
        /** @var Reference|null patient The patient this person is related to */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var array<CodeableConcept> relationship The nature of the relationship */
        public array $relationship = [],
        /** @var array<HumanName> name A name associated with the person */
        public array $name = [],
        /** @var array<ContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        public ?AdministrativeGenderType $gender = null,
        /** @var DatePrimitive|null birthDate The date on which the related person was born */
        public ?DatePrimitive $birthDate = null,
        /** @var array<Address> address Address where the related person can be contacted or visited */
        public array $address = [],
        /** @var array<Attachment> photo Image of the person */
        public array $photo = [],
        /** @var Period|null period Period of time that this relationship is considered valid */
        public ?Period $period = null,
        /** @var array<RelatedPersonCommunication> communication A language which may be used to communicate with about the patient's health */
        public array $communication = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
