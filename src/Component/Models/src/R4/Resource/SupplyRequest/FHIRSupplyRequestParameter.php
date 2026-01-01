<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Specific parameters for the ordered item.  For example, the size of the indicated item.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SupplyRequest', elementPath: 'SupplyRequest.parameter', fhirVersion: 'R4')]
class FHIRSupplyRequestParameter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Item detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean valueX Value of detail */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
