<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The teardown action will only contain an operation.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.teardown.action', fhirVersion: 'R5')]
class FHIRTestScriptTeardownAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTestScriptSetupActionOperation|null operation The teardown operation to perform */
        #[NotBlank]
        public ?FHIRTestScriptSetupActionOperation $operation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
