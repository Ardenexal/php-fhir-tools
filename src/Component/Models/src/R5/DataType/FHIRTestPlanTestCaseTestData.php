<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TestPlan.testCase.testData
 * @description The test data used in the test case.
 */
class FHIRTestPlanTestCaseTestData extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding type The type of test data description, e.g. 'synthea' */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference content The actual test resources when they exist */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $content = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference sourceX Pointer to a definition of test resources - narrative or structured e.g. synthetic data generation, etc */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $sourceX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
