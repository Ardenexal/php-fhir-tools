<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Specifies descriptive properties of the medicine, such as color, shape, imprints, etc.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.drugCharacteristic', fhirVersion: 'R4')]
class MedicationKnowledgeDrugCharacteristic extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Code specifying the type of characteristic of medication */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|StringPrimitive|string|Quantity|Base64BinaryPrimitive|null valueX Description of the characteristic */
        public CodeableConcept|StringPrimitive|string|Quantity|Base64BinaryPrimitive|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
