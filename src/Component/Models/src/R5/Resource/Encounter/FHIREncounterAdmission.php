<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Details about the stay during which a healthcare service is provided.
 *
 * This does not describe the event of admitting the patient, but rather any information that is relevant from the time of admittance until the time of discharge.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.admission', fhirVersion: 'R5')]
class FHIREncounterAdmission extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null preAdmissionIdentifier Pre-admission identifier */
        public ?\FHIRIdentifier $preAdmissionIdentifier = null,
        /** @var FHIRReference|null origin The location/organization from which the patient came before admission */
        public ?\FHIRReference $origin = null,
        /** @var FHIRCodeableConcept|null admitSource From where patient was admitted (physician referral, transfer) */
        public ?\FHIRCodeableConcept $admitSource = null,
        /** @var FHIRCodeableConcept|null reAdmission Indicates that the patient is being re-admitted */
        public ?\FHIRCodeableConcept $reAdmission = null,
        /** @var FHIRReference|null destination Location/organization to which the patient is discharged */
        public ?\FHIRReference $destination = null,
        /** @var FHIRCodeableConcept|null dischargeDisposition Category or kind of location after discharge */
        public ?\FHIRCodeableConcept $dischargeDisposition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
