<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An abstract server used in operations within this test script in the origin element.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.origin', fhirVersion: 'R4')]
class TestScriptOrigin extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var int|null index The index of the abstract origin server starting at 1 */
        #[NotBlank]
        public ?int $index = null,
        /** @var Coding|null profile FHIR-Client | FHIR-SDC-FormFiller */
        #[NotBlank]
        public ?Coding $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
