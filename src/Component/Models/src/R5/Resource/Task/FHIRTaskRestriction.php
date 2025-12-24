<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;

/**
 * @description If the Task.focus is a request resource and the task is seeking fulfillment (i.e. is asking for the request to be actioned), this element identifies any limitations on what parts of the referenced request should be actioned.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Task', elementPath: 'Task.restriction', fhirVersion: 'R5')]
class FHIRTaskRestriction extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null repetitions How many times to repeat */
        public ?FHIRPositiveInt $repetitions = null,
        /** @var FHIRPeriod|null period When fulfillment is sought */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRReference> recipient For whom is fulfillment sought? */
        public array $recipient = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
