<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each resource and each version that is present in the workflow.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance', fhirVersion: 'R4')]
class ExampleScenarioInstance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null resourceId The id of the resource for referencing */
        #[NotBlank]
        public StringPrimitive|string|null $resourceId = null,
        /** @var ResourceTypeType|null resourceType The type of the resource */
        #[NotBlank]
        public ?ResourceTypeType $resourceType = null,
        /** @var StringPrimitive|string|null name A short name for the resource instance */
        public StringPrimitive|string|null $name = null,
        /** @var MarkdownPrimitive|null description Human-friendly description of the resource instance */
        public ?MarkdownPrimitive $description = null,
        /** @var array<ExampleScenarioInstanceVersion> version A specific version of the resource */
        public array $version = [],
        /** @var array<ExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
        public array $containedInstance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
