<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A test executed from the test script.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.test', fhirVersion: 'R4')]
class TestReportTest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Tracking/logging name of this test */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Tracking/reporting short description of the test */
        public StringPrimitive|string|null $description = null,
        /** @var array<TestReportTestAction> action A test operation or assert that was performed */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
