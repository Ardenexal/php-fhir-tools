<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Resources contained in the instance (e.g. the observations contained in a bundle).
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance.containedInstance', fhirVersion: 'R4')]
class FHIRExampleScenarioInstanceContainedInstance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null resourceId Each resource contained in the instance */
        #[NotBlank]
        public \FHIRString|string|null $resourceId = null,
        /** @var FHIRString|string|null versionId A specific version of a resource contained in the instance */
        public \FHIRString|string|null $versionId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
