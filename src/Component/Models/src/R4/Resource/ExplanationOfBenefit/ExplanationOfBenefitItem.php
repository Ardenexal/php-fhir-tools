<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description A claim line. Either a simple (a product or service) or a 'group' of details which can also be a simple items or groups of sub-details.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item', fhirVersion: 'R4')]
class ExplanationOfBenefitItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive sequence Item instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $sequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> careTeamSequence Applicable care team members */
		public array $careTeamSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> diagnosisSequence Applicable diagnoses */
		public array $diagnosisSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> procedureSequence Applicable procedures */
		public array $procedureSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> informationSequence Applicable exception and supporting information */
		public array $informationSequence = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productOrService Billing, service, product, or drug code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> modifier Product or service billing modifiers */
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> udi Unique device identifier */
		public array $udi = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept bodySite Anatomical location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subSite Anatomical sub-location */
		public array $subSite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> encounter Encounters related to this billed item */
		public array $encounter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication> adjudication Adjudication details */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemDetail> detail Additional items */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
