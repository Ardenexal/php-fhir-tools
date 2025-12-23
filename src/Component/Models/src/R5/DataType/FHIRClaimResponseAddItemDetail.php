<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ClaimResponse.addItem.detail
 * @description The second-tier service adjudications for payor added services.
 */
class FHIRClaimResponseAddItemDetail extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrService = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrServiceEnd End of a range of codes */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrServiceEnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal factor Price scaling factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney tax Total tax */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $tax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $net = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemReviewOutcome reviewOutcome Added items detail level adjudication results */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemReviewOutcome $reviewOutcome = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemAdjudication> adjudication Added items detail adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseAddItemDetailSubDetail> subDetail Insurer added line items */
		public array $subDetail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
