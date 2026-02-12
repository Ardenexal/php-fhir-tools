<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest;

/**
 * @description Indicates the specific details for the dispense or medication supply part of a medication request (also known as a Medication Prescription or Medication Order).  Note that this information is not always sent with the order.  There may be in some settings (e.g. hospitals) institutional or system support for completing the dispense details in the pharmacy department.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.dispenseRequest', fhirVersion: 'R4')]
class MedicationRequestDispenseRequest extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest\MedicationRequestDispenseRequestInitialFill initialFill First fill details */
		public ?MedicationRequestDispenseRequestInitialFill $initialFill = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration dispenseInterval Minimum period of time between dispenses */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $dispenseInterval = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period validityPeriod Time period supply is authorized for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $validityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive numberOfRepeatsAllowed Number of refills authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $numberOfRepeatsAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Amount of medication to supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration expectedSupplyDuration Number of days supply per dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $expectedSupplyDuration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performer Intended dispenser */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performer = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
