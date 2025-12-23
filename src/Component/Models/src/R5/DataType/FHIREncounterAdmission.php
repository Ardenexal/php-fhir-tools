<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Encounter.admission
 * @description Details about the stay during which a healthcare service is provided.

This does not describe the event of admitting the patient, but rather any information that is relevant from the time of admittance until the time of discharge.
 */
class FHIREncounterAdmission extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier preAdmissionIdentifier Pre-admission identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $preAdmissionIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference origin The location/organization from which the patient came before admission */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept admitSource From where patient was admitted (physician referral, transfer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $admitSource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept reAdmission Indicates that the patient is being re-admitted */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $reAdmission = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference destination Location/organization to which the patient is discharged */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept dischargeDisposition Category or kind of location after discharge */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $dischargeDisposition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
