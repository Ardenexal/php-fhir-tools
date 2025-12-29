<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Details about the admission to a healthcare service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.hospitalization', fhirVersion: 'R4B')]
class FHIREncounterHospitalization extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier preAdmissionIdentifier Pre-admission identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier $preAdmissionIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference origin The location/organization from which the patient came before admission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept admitSource From where patient was admitted (physician referral, transfer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $admitSource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept reAdmission The type of hospital re-admission that has occurred (if any). If the value is absent, then this is not identified as a readmission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $reAdmission = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> dietPreference Diet preferences reported by the patient */
		public array $dietPreference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
		public array $specialCourtesy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc. */
		public array $specialArrangement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference destination Location/organization to which the patient is discharged */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept dischargeDisposition Category or kind of location after discharge */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $dischargeDisposition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
