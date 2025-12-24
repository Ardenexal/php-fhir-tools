<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description A test executed from the test script.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.test', fhirVersion: 'R4B')]
class FHIRTestReportTest extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Tracking/logging name of this test */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null description Tracking/reporting short description of the test */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRTestReportTestAction> action A test operation or assert that was performed */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
