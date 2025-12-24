<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the observation(s) that triggered the performance of this observation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.triggeredBy', fhirVersion: 'R5')]
class FHIRObservationTriggeredBy extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null observation Triggering observation */
        #[NotBlank]
        public ?FHIRReference $observation = null,
        /** @var FHIRTriggeredBytypeType|null type reflex | repeat | re-run */
        #[NotBlank]
        public ?FHIRTriggeredBytypeType $type = null,
        /** @var FHIRString|string|null reason Reason that the observation was triggered */
        public FHIRString|string|null $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
