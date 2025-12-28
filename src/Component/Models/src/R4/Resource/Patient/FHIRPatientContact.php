<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A contact party (e.g. guardian, partner, friend) for the patient.
 */
#[FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.contact', fhirVersion: 'R4')]
class FHIRPatientContact extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> relationship The kind of relationship */
        public array $relationship = [],
        /** @var FHIRHumanName|null name A name associated with the contact person */
        public ?\FHIRHumanName $name = null,
        /** @var array<FHIRContactPoint> telecom A contact detail for the person */
        public array $telecom = [],
        /** @var FHIRAddress|null address Address for the contact person */
        public ?\FHIRAddress $address = null,
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?\FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRReference|null organization Organization that is associated with the contact */
        public ?\FHIRReference $organization = null,
        /** @var FHIRPeriod|null period The period during which this contact person or organization is valid to be contacted relating to this patient */
        public ?\FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
