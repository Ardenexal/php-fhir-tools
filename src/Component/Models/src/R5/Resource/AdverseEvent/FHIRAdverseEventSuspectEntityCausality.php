<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description Information on the possible cause of the event.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity.causality', fhirVersion: 'R5')]
class FHIRAdverseEventSuspectEntityCausality extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null assessmentMethod Method of evaluating the relatedness of the suspected entity to the event */
        public ?FHIRCodeableConcept $assessmentMethod = null,
        /** @var FHIRCodeableConcept|null entityRelatedness Result of the assessment regarding the relatedness of the suspected entity to the event */
        public ?FHIRCodeableConcept $entityRelatedness = null,
        /** @var FHIRReference|null author Author of the information on the possible cause of the event */
        public ?FHIRReference $author = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
