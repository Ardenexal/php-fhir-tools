<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description The test assertions - the expectations of test results from the execution of the test case.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase.assertion', fhirVersion: 'R5')]
class FHIRTestPlanTestCaseAssertion extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> type Assertion type - for example 'informative' or 'required' */
        public array $type = [],
        /** @var array<FHIRCodeableReference> object The focus or object of the assertion */
        public array $object = [],
        /** @var array<FHIRCodeableReference> result The actual result assertion */
        public array $result = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
