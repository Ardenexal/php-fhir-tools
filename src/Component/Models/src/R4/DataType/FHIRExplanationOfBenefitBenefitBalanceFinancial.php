<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.benefitBalance.financial
 * @description Benefits Used to date.
 */
class FHIRExplanationOfBenefitBenefitBalanceFinancial extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Benefit classification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney allowedX Benefits allowed */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney|null $allowedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney usedX Benefits used */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney|null $usedX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
