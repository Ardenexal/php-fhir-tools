<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-complex-type SubstanceAmount.referenceRange
 * @description Reference range of possible or expected values.
 */
class FHIRSubstanceAmountReferenceRange extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity lowLimit Lower limit possible or expected */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $lowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity highLimit Upper limit possible or expected */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $highLimit = null,
	) {
		parent::__construct($id, $extension);
	}
}
