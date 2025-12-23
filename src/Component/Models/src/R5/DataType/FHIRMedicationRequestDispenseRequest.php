<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationRequest.dispenseRequest
 * @description Indicates the specific details for the dispense or medication supply part of a medication request (also known as a Medication Prescription or Medication Order).  Note that this information is not always sent with the order.  There may be in some settings (e.g. hospitals) institutional or system support for completing the dispense details in the pharmacy department.
 */
class FHIRMedicationRequestDispenseRequest extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationRequestDispenseRequestInitialFill initialFill First fill details */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationRequestDispenseRequestInitialFill $initialFill = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration dispenseInterval Minimum period of time between dispenses */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $dispenseInterval = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod validityPeriod Time period supply is authorized for */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $validityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt numberOfRepeatsAllowed Number of refills authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $numberOfRepeatsAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity Amount of medication to supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration expectedSupplyDuration Number of days supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $expectedSupplyDuration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference dispenser Intended performer of dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $dispenser = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> dispenserInstruction Additional information for the dispenser */
		public array $dispenserInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept doseAdministrationAid Type of adherence packaging to use for the dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $doseAdministrationAid = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
