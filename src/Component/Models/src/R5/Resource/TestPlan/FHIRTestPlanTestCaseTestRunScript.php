<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The test cases in a structured language e.g. gherkin, Postman, or FHIR TestScript.
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase.testRun.script', fhirVersion: 'R5')]
class FHIRTestPlanTestCaseTestRunScript extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null language The language for the test cases e.g. 'gherkin', 'testscript' */
        public ?FHIRCodeableConcept $language = null,
        /** @var FHIRString|string|FHIRReference|null sourceX The actual content of the cases - references to TestScripts or externally defined content */
        public FHIRString|string|FHIRReference|null $sourceX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
