<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;

/**
 * @description Specifics for when this is an interaction.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.interaction', fhirVersion: 'R4B')]
class FHIRClinicalUseDefinitionInteraction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRClinicalUseDefinitionInteractionInteractant> interactant The specific medication, food, substance or laboratory test that interacts */
        public array $interactant = [],
        /** @var FHIRCodeableConcept|null type The type of the interaction e.g. drug-drug interaction, drug-lab test interaction */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableReference|null effect The effect of the interaction, for example "reduced gastric absorption of primary medication" */
        public ?FHIRCodeableReference $effect = null,
        /** @var FHIRCodeableConcept|null incidence The incidence of the interaction, e.g. theoretical, observed */
        public ?FHIRCodeableConcept $incidence = null,
        /** @var array<FHIRCodeableConcept> management Actions for managing the interaction */
        public array $management = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
