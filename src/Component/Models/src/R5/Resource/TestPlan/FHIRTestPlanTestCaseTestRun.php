<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description The actual test to be executed.
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase.testRun', fhirVersion: 'R5')]
class FHIRTestPlanTestCaseTestRun extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null narrative The narrative description of the tests */
        public ?FHIRMarkdown $narrative = null,
        /** @var FHIRTestPlanTestCaseTestRunScript|null script The test cases in a structured language e.g. gherkin, Postman, or FHIR TestScript */
        public ?FHIRTestPlanTestCaseTestRunScript $script = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
