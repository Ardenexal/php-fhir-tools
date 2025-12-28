<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A claim line. Either a simple  product or service or a 'group' of details which can each be a simple items or groups of sub-details.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.item', fhirVersion: 'R5')]
class FHIRClaimItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt sequence Item instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $sequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt> careTeamSequence Applicable careTeam members */
		public array $careTeamSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt> diagnosisSequence Applicable diagnoses */
		public array $diagnosisSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt> procedureSequence Applicable procedures */
		public array $procedureSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt> informationSequence Applicable exception and supporting information */
		public array $informationSequence = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept revenue Revenue or cost center code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $productOrService = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept productOrServiceEnd End of a range of codes */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $productOrServiceEnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> request Request or Referral for Service */
		public array $request = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> modifier Product or service billing modifiers */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> programCode Program the product or service is provided under */
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod servicedX Date or dates of service or product delivery */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|null $servicedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference locationX Place of service or where product was supplied */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $locationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney patientPaid Paid by the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $patientPaid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity Count of products or services */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney unitPrice Fee, charge or cost per item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal factor Price scaling factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney tax Total tax */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $tax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney net Total item cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $net = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> udi Unique device identifier */
		public array $udi = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimItemBodySite> bodySite Anatomical location */
		public array $bodySite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> encounter Encounters associated with the listed treatments */
		public array $encounter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimItemDetail> detail Product or service provided */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
