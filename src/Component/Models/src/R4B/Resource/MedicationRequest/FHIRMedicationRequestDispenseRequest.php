<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Indicates the specific details for the dispense or medication supply part of a medication request (also known as a Medication Prescription or Medication Order).  Note that this information is not always sent with the order.  There may be in some settings (e.g. hospitals) institutional or system support for completing the dispense details in the pharmacy department.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.dispenseRequest', fhirVersion: 'R4B')]
class FHIRMedicationRequestDispenseRequest extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationRequestDispenseRequestInitialFill initialFill First fill details */
		public ?FHIRMedicationRequestDispenseRequestInitialFill $initialFill = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration dispenseInterval Minimum period of time between dispenses */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration $dispenseInterval = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod validityPeriod Time period supply is authorized for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod $validityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt numberOfRepeatsAllowed Number of refills authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt $numberOfRepeatsAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity quantity Amount of medication to supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration expectedSupplyDuration Number of days supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration $expectedSupplyDuration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference performer Intended dispenser */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $performer = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
