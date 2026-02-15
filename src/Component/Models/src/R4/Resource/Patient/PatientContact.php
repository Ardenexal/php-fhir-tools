<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description A contact party (e.g. guardian, partner, friend) for the patient.
 */
#[FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.contact', fhirVersion: 'R4')]
class PatientContact extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> relationship The kind of relationship */
        public array $relationship = [],
        /** @var HumanName|null name A name associated with the contact person */
        public ?HumanName $name = null,
        /** @var array<ContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var Address|null address Address for the contact person */
        public ?Address $address = null,
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        public ?AdministrativeGenderType $gender = null,
        /** @var Reference|null organization Organization that is associated with the contact */
        public ?Reference $organization = null,
        /** @var Period|null period The period during which this contact person or organization is valid to be contacted relating to this patient */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
