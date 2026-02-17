<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description Reference range of possible or expected values.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'SubstanceAmount.referenceRange', fhirVersion: 'R4')]
class SubstanceAmountReferenceRange extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity lowLimit Lower limit possible or expected */
		public ?Quantity $lowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity highLimit Upper limit possible or expected */
		public ?Quantity $highLimit = null,
	) {
		parent::__construct($id, $extension);
	}
}
