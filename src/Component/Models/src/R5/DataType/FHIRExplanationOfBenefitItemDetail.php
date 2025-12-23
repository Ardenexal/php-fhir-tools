<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.item.detail
 * @description Second-tier of goods and services.
 */
class FHIRExplanationOfBenefitItemDetail extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt sequence Product or service provided */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $sequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrService = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrServiceEnd End of a range of codes */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrServiceEnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney patientPaid Paid by the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $patientPaid = null,
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> udi Unique device identifier */
		public array $udi = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExplanationOfBenefitItemReviewOutcome reviewOutcome Detail level adjudication results */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExplanationOfBenefitItemReviewOutcome $reviewOutcome = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExplanationOfBenefitItemAdjudication> adjudication Detail level adjudication details */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExplanationOfBenefitItemDetailSubDetail> subDetail Additional items */
		public array $subDetail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
