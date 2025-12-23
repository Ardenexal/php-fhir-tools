<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element CoverageEligibilityResponse.insurance
 * @description Financial instruments for reimbursement for the health care products and services.
 */
class FHIRCoverageEligibilityResponseInsurance extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference coverage Insurance information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $coverage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean inforce Coverage inforce indicator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $inforce = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod benefitPeriod When the benefits are applicable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod $benefitPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoverageEligibilityResponseInsuranceItem> item Benefits and authorization details */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
