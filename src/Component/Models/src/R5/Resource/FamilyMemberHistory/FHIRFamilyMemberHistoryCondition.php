<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The significant Conditions (or condition) that the family member had. This is a repeating section to allow a system to represent more than one condition per resource, though there is nothing stopping multiple resources - one per condition.
 */
#[FHIRBackboneElement(parentResource: 'FamilyMemberHistory', elementPath: 'FamilyMemberHistory.condition', fhirVersion: 'R5')]
class FHIRFamilyMemberHistoryCondition extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Condition suffered by relation */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null outcome deceased | permanent disability | etc */
        public ?\FHIRCodeableConcept $outcome = null,
        /** @var FHIRBoolean|null contributedToDeath Whether the condition contributed to the cause of death */
        public ?\FHIRBoolean $contributedToDeath = null,
        /** @var FHIRAge|FHIRRange|FHIRPeriod|FHIRString|string|null onsetX When condition first manifested */
        public \FHIRAge|\FHIRRange|\FHIRPeriod|\FHIRString|string|null $onsetX = null,
        /** @var array<FHIRAnnotation> note Extra information about condition */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
