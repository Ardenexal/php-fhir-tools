<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description The required criteria to execute the test plan - e.g. preconditions, previous tests...
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.dependency', fhirVersion: 'R5')]
class FHIRTestPlanDependency extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Description of the dependency criterium */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRReference|null predecessor Link to predecessor test plans */
        public ?FHIRReference $predecessor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
