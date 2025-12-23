<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element NutritionProduct.instance
 * @description Conveys instance-level information about this product item. One or several physical, countable instances or occurrences of the product.
 */
class FHIRNutritionProductInstance extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity The amount of items or instances */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier The identifier for the physical instance, typically a serial number or manufacturer number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name The name for the specific product */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string lotNumber The identification of the batch or lot of the product */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime expiry The expiry date or date and time for the product */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $expiry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime useBy The date until which the product is expected to be good for consumption */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $useBy = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $biologicalSourceEvent = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
