<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description A test in this script.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.test', fhirVersion: 'R5')]
class FHIRTestScriptTest extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var array<FHIRTestScriptTestAction> action A test operation or assert to perform */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
