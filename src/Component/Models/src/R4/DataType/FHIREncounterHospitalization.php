<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Encounter.hospitalization
 * @description Details about the admission to a healthcare service.
 */
class FHIREncounterHospitalization extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier preAdmissionIdentifier Pre-admission identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $preAdmissionIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference origin The location/organization from which the patient came before admission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept admitSource From where patient was admitted (physician referral, transfer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $admitSource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept reAdmission The type of hospital re-admission that has occurred (if any). If the value is absent, then this is not identified as a readmission */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $reAdmission = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> dietPreference Diet preferences reported by the patient */
		public array $dietPreference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
		public array $specialCourtesy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc. */
		public array $specialArrangement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference destination Location/organization to which the patient is discharged */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept dischargeDisposition Category or kind of location after discharge */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $dischargeDisposition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
