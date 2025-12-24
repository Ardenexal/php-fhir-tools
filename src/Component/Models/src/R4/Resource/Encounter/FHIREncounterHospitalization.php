<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;

/**
 * @description Details about the admission to a healthcare service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.hospitalization', fhirVersion: 'R4')]
class FHIREncounterHospitalization extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null preAdmissionIdentifier Pre-admission identifier */
        public ?FHIRIdentifier $preAdmissionIdentifier = null,
        /** @var FHIRReference|null origin The location/organization from which the patient came before admission */
        public ?FHIRReference $origin = null,
        /** @var FHIRCodeableConcept|null admitSource From where patient was admitted (physician referral, transfer) */
        public ?FHIRCodeableConcept $admitSource = null,
        /** @var FHIRCodeableConcept|null reAdmission The type of hospital re-admission that has occurred (if any). If the value is absent, then this is not identified as a readmission */
        public ?FHIRCodeableConcept $reAdmission = null,
        /** @var array<FHIRCodeableConcept> dietPreference Diet preferences reported by the patient */
        public array $dietPreference = [],
        /** @var array<FHIRCodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
        public array $specialCourtesy = [],
        /** @var array<FHIRCodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc. */
        public array $specialArrangement = [],
        /** @var FHIRReference|null destination Location/organization to which the patient is discharged */
        public ?FHIRReference $destination = null,
        /** @var FHIRCodeableConcept|null dischargeDisposition Category or kind of location after discharge */
        public ?FHIRCodeableConcept $dischargeDisposition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
