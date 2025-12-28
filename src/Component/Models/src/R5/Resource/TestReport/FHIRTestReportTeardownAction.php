<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The teardown action will only contain an operation.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.teardown.action', fhirVersion: 'R5')]
class FHIRTestReportTeardownAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTestReportSetupActionOperation|null operation The teardown operation performed */
        #[NotBlank]
        public ?\FHIRTestReportSetupActionOperation $operation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
