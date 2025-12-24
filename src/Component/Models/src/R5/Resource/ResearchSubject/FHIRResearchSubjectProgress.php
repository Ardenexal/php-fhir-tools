<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description The current state (status) of the subject and resons for status change where appropriate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchSubject', elementPath: 'ResearchSubject.progress', fhirVersion: 'R5')]
class FHIRResearchSubjectProgress extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type state | milestone */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null subjectState candidate | eligible | follow-up | ineligible | not-registered | off-study | on-study | on-study-intervention | on-study-observation | pending-on-study | potential-candidate | screening | withdrawn */
        public ?FHIRCodeableConcept $subjectState = null,
        /** @var FHIRCodeableConcept|null milestone SignedUp | Screened | Randomized */
        public ?FHIRCodeableConcept $milestone = null,
        /** @var FHIRCodeableConcept|null reason State change reason */
        public ?FHIRCodeableConcept $reason = null,
        /** @var FHIRDateTime|null startDate State change date */
        public ?FHIRDateTime $startDate = null,
        /** @var FHIRDateTime|null endDate State change date */
        public ?FHIRDateTime $endDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
