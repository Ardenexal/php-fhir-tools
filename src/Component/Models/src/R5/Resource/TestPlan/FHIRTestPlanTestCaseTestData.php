<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The test data used in the test case.
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase.testData', fhirVersion: 'R5')]
class FHIRTestPlanTestCaseTestData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null type The type of test data description, e.g. 'synthea' */
        #[NotBlank]
        public ?\FHIRCoding $type = null,
        /** @var FHIRReference|null content The actual test resources when they exist */
        public ?\FHIRReference $content = null,
        /** @var FHIRString|string|FHIRReference|null sourceX Pointer to a definition of test resources - narrative or structured e.g. synthetic data generation, etc */
        public \FHIRString|string|\FHIRReference|null $sourceX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
