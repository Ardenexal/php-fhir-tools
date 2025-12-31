<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @description Reference range of possible or expected values.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'SubstanceAmount.referenceRange', fhirVersion: 'R4B')]
class FHIRSubstanceAmountReferenceRange extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity lowLimit Lower limit possible or expected */
		public ?FHIRQuantity $lowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity highLimit Upper limit possible or expected */
		public ?FHIRQuantity $highLimit = null,
	) {
		parent::__construct($id, $extension);
	}
}
