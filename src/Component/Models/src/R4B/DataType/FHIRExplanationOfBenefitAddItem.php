<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.addItem
 * @description The first-tier service adjudications for payor added product or service lines.
 */
class FHIRExplanationOfBenefitAddItem extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt> itemSequence Item sequence number */
		public array $itemSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt> detailSequence Detail sequence number */
		public array $detailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt> subDetailSequence Subdetail sequence number */
		public array $subDetailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> provider Authorized providers */
		public array $provider = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod servicedX Date or dates of service or product delivery */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|null $servicedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference locationX Place of service or where product was supplied */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $locationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal factor Price scaling factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney $net = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept bodySite Anatomical location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> subSite Anatomical sub-location */
		public array $subSite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExplanationOfBenefitAddItemDetail> detail Insurer added line items */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
