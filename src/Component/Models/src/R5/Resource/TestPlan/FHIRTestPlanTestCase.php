<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The individual test cases that are part of this plan, when they they are made explicit.
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase', fhirVersion: 'R5')]
class FHIRTestPlanTestCase extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null sequence Sequence of test case in the test plan */
        public ?\FHIRInteger $sequence = null,
        /** @var array<FHIRReference> scope The scope or artifact covered by the case */
        public array $scope = [],
        /** @var array<FHIRTestPlanTestCaseDependency> dependency Required criteria to execute the test case */
        public array $dependency = [],
        /** @var array<FHIRTestPlanTestCaseTestRun> testRun The actual test to be executed */
        public array $testRun = [],
        /** @var array<FHIRTestPlanTestCaseTestData> testData The test data used in the test case */
        public array $testData = [],
        /** @var array<FHIRTestPlanTestCaseAssertion> assertion Test assertions or expectations */
        public array $assertion = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
