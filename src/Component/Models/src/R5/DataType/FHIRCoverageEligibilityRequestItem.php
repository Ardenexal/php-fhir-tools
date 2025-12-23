<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CoverageEligibilityRequest.item
 * @description Service categories or billable services for which benefit details and/or an authorization prior to service delivery may be required by the payor.
 */
class FHIRCoverageEligibilityRequestItem extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> supportingInfoSequence Applicable exception or supporting information */
		public array $supportingInfoSequence = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> modifier Product or service billing modifiers */
		public array $modifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference provider Perfoming practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference facility Servicing facility */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $facility = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoverageEligibilityRequestItemDiagnosis> diagnosis Applicable diagnosis */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> detail Product or service details */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
