<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes the entity that is suspected to have caused the adverse event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity', fhirVersion: 'R5')]
class FHIRAdverseEventSuspectEntity extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|FHIRReference|null instanceX Refers to the specific entity that caused the adverse event */
        #[NotBlank]
        public \FHIRCodeableConcept|\FHIRReference|null $instanceX = null,
        /** @var FHIRAdverseEventSuspectEntityCausality|null causality Information on the possible cause of the event */
        public ?\FHIRAdverseEventSuspectEntityCausality $causality = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
