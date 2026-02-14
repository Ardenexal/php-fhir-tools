<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AdverseEvent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Information on the possible cause of the event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity.causality', fhirVersion: 'R4')]
class AdverseEventSuspectEntityCausality extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null assessment Assessment of if the entity caused the event */
        public ?CodeableConcept $assessment = null,
        /** @var StringPrimitive|string|null productRelatedness AdverseEvent.suspectEntity.causalityProductRelatedness */
        public StringPrimitive|string|null $productRelatedness = null,
        /** @var Reference|null author AdverseEvent.suspectEntity.causalityAuthor */
        public ?Reference $author = null,
        /** @var CodeableConcept|null method ProbabilityScale | Bayesian | Checklist */
        public ?CodeableConcept $method = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
