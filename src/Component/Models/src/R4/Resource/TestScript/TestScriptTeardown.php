<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A series of operations required to clean up after all the tests are executed (successfully or otherwise).
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.teardown', fhirVersion: 'R4')]
class TestScriptTeardown extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<TestScriptTeardownAction> action One or more teardown operations to perform */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
