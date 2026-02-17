<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse;

/**
 * @description Financial instruments for reimbursement for the health care products and services.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CoverageEligibilityResponse', elementPath: 'CoverageEligibilityResponse.insurance', fhirVersion: 'R4')]
class CoverageEligibilityResponseInsurance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference coverage Insurance information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $coverage = null,
		/** @var null|bool inforce Coverage inforce indicator */
		public ?bool $inforce = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period benefitPeriod When the benefits are applicable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $benefitPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse\CoverageEligibilityResponseInsuranceItem> item Benefits and authorization details */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
