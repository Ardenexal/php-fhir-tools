<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Ratio
 * @description A relationship of two Quantity values - expressed as a numerator and a denominator.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Ratio', fhirVersion: 'R4B')]
class FHIRRatio extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity numerator Numerator value */
		public ?FHIRQuantity $numerator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity denominator Denominator value */
		public ?FHIRQuantity $denominator = null,
	) {
		parent::__construct($id, $extension);
	}
}
