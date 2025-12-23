<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CoverageEligibilityResponse.insurance.item.benefit
 * @description Benefits used to date.
 */
class FHIRCoverageEligibilityResponseInsuranceItemBenefit extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Benefit classification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney allowedX Benefits allowed */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney|null $allowedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney usedX Benefits used */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney|null $usedX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
