<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem', fhirVersion: 'R4')]
class ExplanationOfBenefitAddItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> itemSequence Item sequence number */
		public array $itemSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> detailSequence Detail sequence number */
		public array $detailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> subDetailSequence Subdetail sequence number */
		public array $subDetailSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> provider Authorized providers */
		public array $provider = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productOrService Billing, service, product, or drug code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> modifier Service/Product billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period servicedX Date or dates of service or product delivery */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $servicedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference locationX Place of service or where product was supplied */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $locationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $unitPrice = null,
		/** @var null|float factor Price scaling factor */
		public ?float $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $net = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept bodySite Anatomical location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subSite Anatomical sub-location */
		public array $subSite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItemDetail> detail Insurer added line items */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
