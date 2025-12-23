<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ClaimResponse.addItem
 * @description The first-tier service adjudications for payor added product or service lines.
 */
class FHIRClaimResponseAddItem extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> itemSequence Item sequence number */
		public array $itemSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> detailSequence Detail sequence number */
		public array $detailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> subdetailSequence Subdetail sequence number */
		public array $subdetailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> provider Authorized providers */
		public array $provider = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrService = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrServiceEnd End of a range of codes */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrServiceEnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> request Request or Referral for Service */
		public array $request = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod servicedX Date or dates of service or product delivery */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|null $servicedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference locationX Place of service or where product was supplied */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $locationX = null,
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseAddItemBodySite> bodySite Anatomical location */
		public array $bodySite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemReviewOutcome reviewOutcome Added items adjudication results */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemReviewOutcome $reviewOutcome = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemAdjudication> adjudication Added items adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseAddItemDetail> detail Insurer added line details */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
