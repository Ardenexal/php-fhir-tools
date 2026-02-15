<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Task;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;

/**
 * @description If the Task.focus is a request resource and the task is seeking fulfillment (i.e. is asking for the request to be actioned), this element identifies any limitations on what parts of the referenced request should be actioned.
 */
#[FHIRBackboneElement(parentResource: 'Task', elementPath: 'Task.restriction', fhirVersion: 'R4')]
class TaskRestriction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null repetitions How many times to repeat */
        public ?PositiveIntPrimitive $repetitions = null,
        /** @var Period|null period When fulfillment sought */
        public ?Period $period = null,
        /** @var array<Reference> recipient For whom is fulfillment sought? */
        public array $recipient = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
