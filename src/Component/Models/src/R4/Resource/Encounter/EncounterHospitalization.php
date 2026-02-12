<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

/**
 * @description Details about the admission to a healthcare service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.hospitalization', fhirVersion: 'R4')]
class EncounterHospitalization extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier preAdmissionIdentifier Pre-admission identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $preAdmissionIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference origin The location/organization from which the patient came before admission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept admitSource From where patient was admitted (physician referral, transfer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $admitSource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reAdmission The type of hospital re-admission that has occurred (if any). If the value is absent, then this is not identified as a readmission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reAdmission = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> dietPreference Diet preferences reported by the patient */
		public array $dietPreference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
		public array $specialCourtesy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc. */
		public array $specialArrangement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference destination Location/organization to which the patient is discharged */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept dischargeDisposition Category or kind of location after discharge */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $dischargeDisposition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
