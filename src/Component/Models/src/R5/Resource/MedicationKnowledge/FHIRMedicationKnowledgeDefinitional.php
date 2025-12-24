<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description Along with the link to a Medicinal Product Definition resource, this information provides common definitional elements that are needed to understand the specific medication that is being described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.definitional', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeDefinitional extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> definition Definitional resources that provide more information about this medication */
        public array $definition = [],
        /** @var FHIRCodeableConcept|null doseForm powder | tablets | capsule + */
        public ?FHIRCodeableConcept $doseForm = null,
        /** @var array<FHIRCodeableConcept> intendedRoute The intended or approved route of administration */
        public array $intendedRoute = [],
        /** @var array<FHIRMedicationKnowledgeDefinitionalIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var array<FHIRMedicationKnowledgeDefinitionalDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
        public array $drugCharacteristic = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
