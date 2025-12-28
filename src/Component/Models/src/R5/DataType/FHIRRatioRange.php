<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RatioRange
 * @description A range of ratios expressed as a low and high numerator and a denominator.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RatioRange', fhirVersion: 'R5')]
class FHIRRatioRange extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity lowNumerator Low Numerator limit */
		public ?FHIRQuantity $lowNumerator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity highNumerator High Numerator limit */
		public ?FHIRQuantity $highNumerator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity denominator Denominator value */
		public ?FHIRQuantity $denominator = null,
	) {
		parent::__construct($id, $extension);
	}
}
