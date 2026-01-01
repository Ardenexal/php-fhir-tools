<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Financial instruments for reimbursement for the health care products and services.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CoverageEligibilityRequest', elementPath: 'CoverageEligibilityRequest.insurance', fhirVersion: 'R4')]
class FHIRCoverageEligibilityRequestInsurance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean focal Applicable coverage */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $focal = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference coverage Insurance information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $coverage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string businessArrangement Additional provider contract number */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $businessArrangement = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
