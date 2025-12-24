<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description A significant action that occurs as part of the process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step', fhirVersion: 'R5')]
class FHIRExampleScenarioProcessStep extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null number Sequential number of the step */
        public FHIRString|string|null $number = null,
        /** @var FHIRExampleScenarioProcess|null process Step is nested process */
        public ?FHIRExampleScenarioProcess $process = null,
        /** @var FHIRCanonical|null workflow Step is nested workflow */
        public ?FHIRCanonical $workflow = null,
        /** @var FHIRExampleScenarioProcessStepOperation|null operation Step is simple action */
        public ?FHIRExampleScenarioProcessStepOperation $operation = null,
        /** @var array<FHIRExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
        public array $alternative = [],
        /** @var FHIRBoolean|null pause Pause in the flow? */
        public ?FHIRBoolean $pause = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
