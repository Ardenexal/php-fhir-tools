<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A system or person who shares or receives an instance within the scenario.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.actor', fhirVersion: 'R5')]
class FHIRExampleScenarioActor extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null key ID or acronym of the actor */
        #[NotBlank]
        public \FHIRString|string|null $key = null,
        /** @var FHIRExampleScenarioActorTypeType|null type person | system */
        #[NotBlank]
        public ?\FHIRExampleScenarioActorTypeType $type = null,
        /** @var FHIRString|string|null title Label for actor when rendering */
        #[NotBlank]
        public \FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Details about actor */
        public ?\FHIRMarkdown $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
