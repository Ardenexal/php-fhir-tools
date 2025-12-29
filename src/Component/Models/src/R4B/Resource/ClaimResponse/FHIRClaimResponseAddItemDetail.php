<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The second-tier service adjudications for payor added services.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.addItem.detail', fhirVersion: 'R4B')]
class FHIRClaimResponseAddItemDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal factor Price scaling factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney $net = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClaimResponseItemAdjudication> adjudication Added items detail adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClaimResponseAddItemDetailSubDetail> subDetail Insurer added line items */
		public array $subDetail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
