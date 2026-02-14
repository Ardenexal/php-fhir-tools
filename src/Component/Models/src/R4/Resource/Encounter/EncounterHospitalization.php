<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Details about the admission to a healthcare service.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.hospitalization', fhirVersion: 'R4')]
class EncounterHospitalization extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null preAdmissionIdentifier Pre-admission identifier */
        public ?Identifier $preAdmissionIdentifier = null,
        /** @var Reference|null origin The location/organization from which the patient came before admission */
        public ?Reference $origin = null,
        /** @var CodeableConcept|null admitSource From where patient was admitted (physician referral, transfer) */
        public ?CodeableConcept $admitSource = null,
        /** @var CodeableConcept|null reAdmission The type of hospital re-admission that has occurred (if any). If the value is absent, then this is not identified as a readmission */
        public ?CodeableConcept $reAdmission = null,
        /** @var array<CodeableConcept> dietPreference Diet preferences reported by the patient */
        public array $dietPreference = [],
        /** @var array<CodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
        public array $specialCourtesy = [],
        /** @var array<CodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc. */
        public array $specialArrangement = [],
        /** @var Reference|null destination Location/organization to which the patient is discharged */
        public ?Reference $destination = null,
        /** @var CodeableConcept|null dischargeDisposition Category or kind of location after discharge */
        public ?CodeableConcept $dischargeDisposition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
