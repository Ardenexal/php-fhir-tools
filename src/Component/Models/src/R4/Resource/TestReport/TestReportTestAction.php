<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Action would contain either an operation or an assertion.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.test.action', fhirVersion: 'R4')]
class TestReportTestAction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var TestReportSetupActionOperation|null operation The operation performed */
        public ?TestReportSetupActionOperation $operation = null,
        /** @var TestReportSetupActionAssert|null assert The assertion performed */
        public ?TestReportSetupActionAssert $assert = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
