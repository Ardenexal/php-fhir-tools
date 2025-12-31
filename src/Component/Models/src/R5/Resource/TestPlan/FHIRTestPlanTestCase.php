<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The individual test cases that are part of this plan, when they they are made explicit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase', fhirVersion: 'R5')]
class FHIRTestPlanTestCase extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger sequence Sequence of test case in the test plan */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $sequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> scope The scope or artifact covered by the case */
		public array $scope = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestPlanTestCaseDependency> dependency Required criteria to execute the test case */
		public array $dependency = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestPlanTestCaseTestRun> testRun The actual test to be executed */
		public array $testRun = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestPlanTestCaseTestData> testData The test data used in the test case */
		public array $testData = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestPlanTestCaseAssertion> assertion Test assertions or expectations */
		public array $assertion = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
