<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes the entity that is suspected to have caused the adverse event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity', fhirVersion: 'R4B')]
class FHIRAdverseEventSuspectEntity extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null instance Refers to the specific entity that caused the adverse event */
        #[NotBlank]
        public ?FHIRReference $instance = null,
        /** @var array<FHIRAdverseEventSuspectEntityCausality> causality Information on the possible cause of the event */
        public array $causality = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
