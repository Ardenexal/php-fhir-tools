<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A claim detail line. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.item.detail.subDetail', fhirVersion: 'R4B')]
class FHIRClaimItemDetailSubDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt sequence Item instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal factor Price scaling factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney $net = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> udi Unique device identifier */
		public array $udi = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
