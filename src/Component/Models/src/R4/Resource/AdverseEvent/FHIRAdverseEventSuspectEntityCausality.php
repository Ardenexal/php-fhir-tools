<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Information on the possible cause of the event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity.causality', fhirVersion: 'R4')]
class FHIRAdverseEventSuspectEntityCausality extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null assessment Assessment of if the entity caused the event */
        public ?FHIRCodeableConcept $assessment = null,
        /** @var FHIRString|string|null productRelatedness AdverseEvent.suspectEntity.causalityProductRelatedness */
        public FHIRString|string|null $productRelatedness = null,
        /** @var FHIRReference|null author AdverseEvent.suspectEntity.causalityAuthor */
        public ?FHIRReference $author = null,
        /** @var FHIRCodeableConcept|null method ProbabilityScale | Bayesian | Checklist */
        public ?FHIRCodeableConcept $method = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
