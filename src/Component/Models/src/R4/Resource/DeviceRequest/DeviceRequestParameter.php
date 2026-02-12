<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceRequest;

/**
 * @description Specific parameters for the ordered item.  For example, the prism value for lenses.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceRequest', elementPath: 'DeviceRequest.parameter', fhirVersion: 'R4')]
class DeviceRequestParameter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Device detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|bool valueX Value of detail */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|bool|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
