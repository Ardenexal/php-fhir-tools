<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description The results of the series of required setup operations before the tests were executed.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup', fhirVersion: 'R4')]
class TestReportSetup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<TestReportSetupAction> action A setup operation or assert that was executed */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
