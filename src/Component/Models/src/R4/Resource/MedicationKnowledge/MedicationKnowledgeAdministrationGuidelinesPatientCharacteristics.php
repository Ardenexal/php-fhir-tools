<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Characteristics of the patient that are relevant to the administration guidelines (for example, height, weight, gender, etc.).
 */
#[FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.administrationGuidelines.patientCharacteristics',
    fhirVersion: 'R4',
)]
class MedicationKnowledgeAdministrationGuidelinesPatientCharacteristics extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|Quantity|null characteristicX Specific characteristic that is relevant to the administration guideline */
        #[NotBlank]
        public CodeableConcept|Quantity|null $characteristicX = null,
        /** @var array<StringPrimitive|string> value The specific characteristic */
        public array $value = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
