<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specific version of the resource.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance.version', fhirVersion: 'R4')]
class FHIRExampleScenarioInstanceVersion extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null versionId The identifier of a specific version of a resource */
        #[NotBlank]
        public \FHIRString|string|null $versionId = null,
        /** @var FHIRMarkdown|null description The description of the resource version */
        #[NotBlank]
        public ?\FHIRMarkdown $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
