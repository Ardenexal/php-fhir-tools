<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The teardown action will only contain an operation.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.teardown.action', fhirVersion: 'R4')]
class TestReportTeardownAction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var TestReportSetupActionOperation|null operation The teardown operation performed */
        #[NotBlank]
        public ?TestReportSetupActionOperation $operation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
