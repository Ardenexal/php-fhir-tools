<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice;

/**
 * @description Each line item represents one charge for goods and services rendered. Details such as date, code and amount are found in the referenced ChargeItem resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Invoice', elementPath: 'Invoice.lineItem', fhirVersion: 'R4')]
class InvoiceLineItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive sequence Sequence number of line item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept chargeItemX Reference to ChargeItem containing details of this line item or an inline billing code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $chargeItemX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceLineItemPriceComponent> priceComponent Components of total line item price */
		public array $priceComponent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
