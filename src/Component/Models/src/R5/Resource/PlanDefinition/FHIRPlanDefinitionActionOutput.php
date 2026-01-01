<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Defines the outputs of the action, if any.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.output', fhirVersion: 'R5')]
class FHIRPlanDefinitionActionOutput extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var FHIRDataRequirement|null requirement What data is provided */
        public ?FHIRDataRequirement $requirement = null,
        /** @var FHIRString|string|null relatedData What data is provided */
        public FHIRString|string|null $relatedData = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
