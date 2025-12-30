<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description The results of the series of operations required to clean up after all the tests were executed (successfully or otherwise).
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.teardown', fhirVersion: 'R5')]
class FHIRTestReportTeardown extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRTestReportTeardownAction> action One or more teardown operations performed */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
