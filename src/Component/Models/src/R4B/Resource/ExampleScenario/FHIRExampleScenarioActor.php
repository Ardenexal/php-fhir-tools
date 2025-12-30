<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExampleScenarioActorTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Actor participating in the resource.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.actor', fhirVersion: 'R4B')]
class FHIRExampleScenarioActor extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null actorId ID or acronym of the actor */
        #[NotBlank]
        public FHIRString|string|null $actorId = null,
        /** @var FHIRExampleScenarioActorTypeType|null type person | entity */
        #[NotBlank]
        public ?FHIRExampleScenarioActorTypeType $type = null,
        /** @var FHIRString|string|null name The name of the actor as shown in the page */
        public FHIRString|string|null $name = null,
        /** @var FHIRMarkdown|null description The description of the actor */
        public ?FHIRMarkdown $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
