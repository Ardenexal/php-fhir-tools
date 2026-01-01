<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description A series of operations required to clean up after all the tests are executed (successfully or otherwise).
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.teardown', fhirVersion: 'R5')]
class FHIRTestScriptTeardown extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRTestScriptTeardownAction> action One or more teardown operations to perform */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
