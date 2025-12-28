<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Action would contain either an operation or an assertion.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action', fhirVersion: 'R4B')]
class FHIRTestReportSetupAction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTestReportSetupActionOperation|null operation The operation to perform */
        public ?\FHIRTestReportSetupActionOperation $operation = null,
        /** @var FHIRTestReportSetupActionAssert|null assert The assertion to perform */
        public ?\FHIRTestReportSetupActionAssert $assert = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
