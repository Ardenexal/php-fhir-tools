<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Actors represent the individuals or groups involved in the execution of the defined set of activities.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.actor', fhirVersion: 'R5')]
class FHIRPlanDefinitionActor extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title User-visible title */
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Describes the actor */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRPlanDefinitionActorOption> option Who or what can be this actor */
        public array $option = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
