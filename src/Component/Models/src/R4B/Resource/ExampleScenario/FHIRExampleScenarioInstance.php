<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each resource and each version that is present in the workflow.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance', fhirVersion: 'R4B')]
class FHIRExampleScenarioInstance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null resourceId The id of the resource for referencing */
        #[NotBlank]
        public FHIRString|string|null $resourceId = null,
        /** @var FHIRResourceTypeType|null resourceType The type of the resource */
        #[NotBlank]
        public ?FHIRResourceTypeType $resourceType = null,
        /** @var FHIRString|string|null name A short name for the resource instance */
        public FHIRString|string|null $name = null,
        /** @var FHIRMarkdown|null description Human-friendly description of the resource instance */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRExampleScenarioInstanceVersion> version A specific version of the resource */
        public array $version = [],
        /** @var array<FHIRExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
        public array $containedInstance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
