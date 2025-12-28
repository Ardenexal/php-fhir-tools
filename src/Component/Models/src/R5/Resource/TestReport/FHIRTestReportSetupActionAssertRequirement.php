<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Links or references providing traceability to the testing requirements for this assert.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action.assert.requirement', fhirVersion: 'R5')]
class FHIRTestReportSetupActionAssertRequirement extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|FHIRCanonical|null linkX Link or reference to the testing requirement */
        public \FHIRUri|\FHIRCanonical|null $linkX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
