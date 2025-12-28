<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Describe the possible undesirable effects (negative outcomes) from the use of the medicinal product as treatment.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.undesirableEffect', fhirVersion: 'R4B')]
class FHIRClinicalUseDefinitionUndesirableEffect extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null symptomConditionEffect The situation in which the undesirable effect may manifest */
        public ?\FHIRCodeableReference $symptomConditionEffect = null,
        /** @var FHIRCodeableConcept|null classification High level classification of the effect */
        public ?\FHIRCodeableConcept $classification = null,
        /** @var FHIRCodeableConcept|null frequencyOfOccurrence How often the effect is seen */
        public ?\FHIRCodeableConcept $frequencyOfOccurrence = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
