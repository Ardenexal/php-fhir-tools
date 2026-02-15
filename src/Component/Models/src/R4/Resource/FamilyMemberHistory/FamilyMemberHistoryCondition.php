<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\FamilyMemberHistory;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The significant Conditions (or condition) that the family member had. This is a repeating section to allow a system to represent more than one condition per resource, though there is nothing stopping multiple resources - one per condition.
 */
#[FHIRBackboneElement(parentResource: 'FamilyMemberHistory', elementPath: 'FamilyMemberHistory.condition', fhirVersion: 'R4')]
class FamilyMemberHistoryCondition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Condition suffered by relation */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var CodeableConcept|null outcome deceased | permanent disability | etc. */
        public ?CodeableConcept $outcome = null,
        /** @var bool|null contributedToDeath Whether the condition contributed to the cause of death */
        public ?bool $contributedToDeath = null,
        /** @var Age|Range|Period|StringPrimitive|string|null onsetX When condition first manifested */
        public Age|Range|Period|StringPrimitive|string|null $onsetX = null,
        /** @var array<Annotation> note Extra information about condition */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
