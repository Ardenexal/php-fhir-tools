<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ExampleScenarioActorTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Actor participating in the resource.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.actor', fhirVersion: 'R4')]
class ExampleScenarioActor extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null actorId ID or acronym of the actor */
        #[NotBlank]
        public StringPrimitive|string|null $actorId = null,
        /** @var ExampleScenarioActorTypeType|null type person | entity */
        #[NotBlank]
        public ?ExampleScenarioActorTypeType $type = null,
        /** @var StringPrimitive|string|null name The name of the actor as shown in the page */
        public StringPrimitive|string|null $name = null,
        /** @var MarkdownPrimitive|null description The description of the actor */
        public ?MarkdownPrimitive $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
